<?php

namespace Tests\Feature;

use App\Contracts\TrustmedisServiceInterface;
use App\Livewire\DoctorTreatment;
use App\Livewire\NurseExamination;
use App\Livewire\PatientKiosk;
use App\Livewire\PatientRegistration;
use App\Models\Clinic;
use App\Models\Patient;
use App\Models\Treatment;
use App\Models\User;
use App\Models\Visit;
use App\Services\MockTrustmedisService;
use App\Services\PatientCodeGenerator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class ClinicFlowTest extends TestCase
{
    use RefreshDatabase;

    protected Clinic $clinic;
    protected User $doctor;
    protected User $nurse;
    protected Treatment $treatment1;
    protected Treatment $treatment2;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');

        $this->clinic = Clinic::create([
            'name' => 'Onew Clinic',
            'clinic_code' => 'ONEW',
            'primary_color' => '#0284C7',
            'secondary_color' => '#0EA5E9',
            'address' => 'Jl. Senopati No. 88, Jakarta Selatan',
            'phone' => '0812-9988-7766',
            'general_consent_text' => 'Persetujuan Tindakan Medis Estetika',
        ]);

        $this->doctor = User::create([
            'clinic_id' => $this->clinic->id,
            'name' => 'dr. Alana Wijaya',
            'email' => 'doctor@onewclinic.com',
            'password' => 'password',
            'role' => 'doctor',
        ]);

        $this->nurse = User::create([
            'clinic_id' => $this->clinic->id,
            'name' => 'Ns. Ratna Dewi',
            'email' => 'nurse@onewclinic.com',
            'password' => 'password',
            'role' => 'nurse',
        ]);

        $this->treatment1 = Treatment::create([
            'clinic_id' => $this->clinic->id,
            'name' => 'Facial Glowing',
            'category' => 'Facial Treatment',
            'price' => 350000,
        ]);

        $this->treatment2 = Treatment::create([
            'clinic_id' => $this->clinic->id,
            'name' => 'Laser Rejuvenation',
            'category' => 'Laser & Light',
            'price' => 1250000,
        ]);
    }

    public function test_patient_code_generator_produces_sequential_codes_with_lock_for_update(): void
    {
        $generator = new PatientCodeGenerator();
        $code1 = $generator->generate($this->clinic);

        $patient1 = Patient::create([
            'clinic_id' => $this->clinic->id,
            'patient_code' => $code1,
            'name' => 'Test Patient 1',
            'phone_number' => '0811111111',
            'gender' => 'female',
            'date_of_birth' => '1995-01-01',
        ]);

        $code2 = $generator->generate($this->clinic);

        $this->assertMatchesRegularExpression('/^ONEW\d{2}-000001$/', $code1);
        $this->assertMatchesRegularExpression('/^ONEW\d{2}-000002$/', $code2);
    }

    public function test_step_1_patient_registration_creates_patient_and_visit(): void
    {
        Livewire::test(PatientRegistration::class)
            ->set('mode', 'new')
            ->set('name', 'Clara Anindya')
            ->set('nik', '3171234567890001')
            ->set('birth_place', 'Jakarta')
            ->set('date_of_birth', '1997-08-15')
            ->set('gender', 'female')
            ->set('phone_number', '08123456789')
            ->set('email', 'clara@example.com')
            ->set('address', 'Kebayoran Baru, Jakarta')
            ->set('emergency_contact_name', 'Budi Anindya')
            ->set('emergency_contact_relation', 'Orang Tua')
            ->set('emergency_contact_phone', '08129876543')
            ->set('doctor_id', $this->doctor->id)
            ->set('assistant_name', 'Ns. Ratna Dewi')
            ->set('agreementChecked', true)
            ->call('registerAndSubmitVisit')
            ->assertHasNoErrors()
            ->assertRedirect();

        $this->assertDatabaseHas('patients', [
            'name' => 'Clara Anindya',
            'nik' => '3171234567890001',
            'phone_number' => '08123456789',
            'emergency_contact_name' => 'Budi Anindya',
        ]);

        $this->assertDatabaseHas('visits', [
            'status' => 'waiting_doctor',
            'consent_signed' => true,
            'doctor_id' => $this->doctor->id,
            'assistant_name' => 'Ns. Ratna Dewi',
        ]);
    }

    public function test_step_2_patient_kiosk_saves_signature_and_updates_status(): void
    {
        $patient = Patient::create([
            'clinic_id' => $this->clinic->id,
            'patient_code' => 'ONEW26-000001',
            'name' => 'Clara Anindya',
            'phone_number' => '08123456789',
            'gender' => 'female',
            'date_of_birth' => '1997-08-15',
        ]);

        $visit = Visit::create([
            'clinic_id' => $this->clinic->id,
            'patient_id' => $patient->id,
            'status' => 'registered',
            'consent_signed' => false,
        ]);

        // Simulated base64 png signature (1x1 transparent pixel)
        $dummySignature = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=';

        Livewire::test(PatientKiosk::class, ['visit' => $visit])
            ->set('agreementChecked', true)
            ->call('saveSignature', $dummySignature)
            ->assertHasNoErrors()
            ->assertSet('isCompleted', true);

        $visit->refresh();
        $this->assertTrue($visit->consent_signed);
        $this->assertEquals('consent_signed', $visit->status);
        $this->assertNotNull($visit->consent_signature_path);
        Storage::disk('public')->assertExists($visit->consent_signature_path);
    }

    public function test_step_3_nurse_examination_saves_photo_before_and_assigns_doctor(): void
    {
        $patient = Patient::create([
            'clinic_id' => $this->clinic->id,
            'patient_code' => 'ONEW26-000001',
            'name' => 'Clara Anindya',
            'phone_number' => '08123456789',
            'gender' => 'female',
            'date_of_birth' => '1997-08-15',
        ]);

        $visit = Visit::create([
            'clinic_id' => $this->clinic->id,
            'patient_id' => $patient->id,
            'status' => 'consent_signed',
            'consent_signed' => true,
        ]);

        $photoBefore = UploadedFile::fake()->create('before.jpg', 100, 'image/jpeg');

        Livewire::test(NurseExamination::class, ['visit_id' => $visit->id])
            ->set('doctor_id', $this->doctor->id)
            ->set('assistant_name', 'Ns. Ratna Dewi')
            ->set('photo_before', $photoBefore)
            ->call('saveExamination')
            ->assertHasNoErrors();

        $visit->refresh();
        $this->assertEquals('waiting_doctor', $visit->status);
        $this->assertEquals($this->doctor->id, $visit->doctor_id);
        $this->assertNotNull($visit->photo_before_path);
        Storage::disk('public')->assertExists($visit->photo_before_path);
    }

    public function test_step_4_doctor_treatment_completes_visit_and_syncs_trustmedis(): void
    {
        $patient = Patient::create([
            'clinic_id' => $this->clinic->id,
            'patient_code' => 'ONEW26-000001',
            'name' => 'Clara Anindya',
            'phone_number' => '08123456789',
            'gender' => 'female',
            'date_of_birth' => '1997-08-15',
        ]);

        $visit = Visit::create([
            'clinic_id' => $this->clinic->id,
            'patient_id' => $patient->id,
            'doctor_id' => $this->doctor->id,
            'nurse_id' => $this->nurse->id,
            'assistant_name' => 'Ns. Ratna Dewi',
            'status' => 'waiting_doctor',
            'consent_signed' => true,
        ]);

        $photoAfter = UploadedFile::fake()->create('after.jpg', 100, 'image/jpeg');

        Livewire::test(DoctorTreatment::class, ['visit_id' => $visit->id])
            ->set('selectedTreatments', [(string) $this->treatment1->id, (string) $this->treatment2->id])
            ->set('treatmentNotes', [
                $this->treatment1->id => 'Serum vit C & ekstrak lidah buaya diaplikasikan',
                $this->treatment2->id => 'Laser Nd:YAG 1064nm, fluens 2.5 J/cm2',
            ])
            ->set('photo_after', $photoAfter)
            ->call('completeVisitAndSync')
            ->assertHasNoErrors()
            ->assertSet('showSuccessModal', true);

        $visit->refresh();
        $this->assertEquals('completed', $visit->status);
        $this->assertEquals('synced', $visit->trustmedis_sync_status);
        $this->assertNotNull($visit->trustmedis_payload);
        $this->assertNotNull($visit->trustmedis_synced_at);
        $this->assertCount(2, $visit->treatments);
        $this->assertNotNull($visit->photo_after_path);
        Storage::disk('public')->assertExists($visit->photo_after_path);
    }

    public function test_step_5_medical_report_pdf_generation(): void
    {
        $patient = Patient::create([
            'clinic_id' => $this->clinic->id,
            'patient_code' => 'ONEW26-000001',
            'name' => 'Clara Anindya',
            'phone_number' => '08123456789',
            'gender' => 'female',
            'date_of_birth' => '1997-08-15',
        ]);

        $visit = Visit::create([
            'clinic_id' => $this->clinic->id,
            'patient_id' => $patient->id,
            'doctor_id' => $this->doctor->id,
            'nurse_id' => $this->nurse->id,
            'assistant_name' => 'Ns. Ratna Dewi',
            'status' => 'completed',
            'consent_signed' => true,
            'trustmedis_sync_status' => 'synced',
        ]);

        $visit->treatments()->attach($this->treatment1->id, [
            'notes' => 'Catatan klinis tindakan',
        ]);

        $response = $this->actingAs($this->doctor)->get(route('reports.visit.pdf', $visit));
        $response->assertStatus(200);
        $this->assertEquals('application/pdf', $response->headers->get('content-type'));
    }

    public function test_auth_login_and_logout_flow(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);

        Livewire::test(\App\Livewire\Auth\Login::class)
            ->set('email', 'doctor@onewclinic.com')
            ->set('password', 'password')
            ->call('login')
            ->assertRedirect(route('dashboard'));

        $this->assertAuthenticatedAs($this->doctor);

        $logoutResponse = $this->post(route('logout'));
        $logoutResponse->assertRedirect('/login');
        $this->assertGuest();
    }

    public function test_doctor_treatment_saves_diagnosis_consumables_and_informed_consent(): void
    {
        $consumable = \App\Models\Consumable::create([
            'clinic_id' => $this->clinic->id,
            'name' => 'SPUYT 1CC',
            'category' => 'Spuit & Needle',
            'unit' => 'Pcs',
            'default_quantity' => 1,
            'is_active' => true,
        ]);

        $patient = Patient::create([
            'clinic_id' => $this->clinic->id,
            'patient_code' => 'ONEW26-000002',
            'name' => 'Maya Putri',
            'phone_number' => '081299998888',
            'gender' => 'female',
            'date_of_birth' => '1996-03-20',
        ]);

        $visit = Visit::create([
            'clinic_id' => $this->clinic->id,
            'patient_id' => $patient->id,
            'status' => 'waiting_doctor',
            'consent_signed' => true,
        ]);

        $dummySignature = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=';

        Livewire::test(DoctorTreatment::class, ['visit_id' => $visit->id])
            ->set('diagnosis_wd', 'Acne Vulgaris Grade II')
            ->set('diagnosis_dd', 'Folliculitis')
            ->set('doctorNotes', 'Pasien disarankan perawatan rutin 2 minggu sekali')
            ->set('selectedTreatments', [(string) $this->treatment1->id])
            ->set('selectedConsumables', [$consumable->id => 2])
            ->set('consent_action_name', 'Facial & Ekstraksi Komedo')
            ->set('consent_recipient_name', 'Maya Putri')
            ->set('patientConsentSignature', $dummySignature)
            ->call('saveInformedConsent')
            ->call('completeVisitAndSync')
            ->assertHasNoErrors();

        $visit->refresh();
        $this->assertEquals('completed', $visit->status);
        $this->assertEquals('Acne Vulgaris Grade II', $visit->diagnosis_wd);
        $this->assertEquals('Folliculitis', $visit->diagnosis_dd);
        $this->assertDatabaseHas('visit_consumables', [
            'visit_id' => $visit->id,
            'consumable_id' => $consumable->id,
            'quantity' => 2,
        ]);
        $this->assertDatabaseHas('informed_consents', [
            'visit_id' => $visit->id,
            'action_name' => 'Facial & Ekstraksi Komedo',
            'recipient_name' => 'Maya Putri',
        ]);
    }

    public function test_superadmin_can_manage_staff_and_consumables_in_settings(): void
    {
        $superadmin = User::create([
            'clinic_id' => $this->clinic->id,
            'name' => 'Super Administrator',
            'email' => 'admin@onewclinic.com',
            'password' => 'password',
            'role' => 'superadmin',
        ]);

        Livewire::actingAs($superadmin)
            ->test(\App\Livewire\ClinicSettings::class)
            // Add new staff
            ->set('staff_name', 'dr. Citra Lestari, Sp.KK')
            ->set('staff_email', 'citra@onewclinic.com')
            ->set('staff_role', 'doctor')
            ->set('staff_password', 'secret123')
            ->call('addStaff')
            ->assertHasNoErrors()
            // Add new consumable BHP
            ->set('consumable_name', 'SERUM HYALURONIC ACID')
            ->set('consumable_category', 'Topikal & Perawatan')
            ->set('consumable_unit', 'Vial')
            ->set('consumable_default_qty', 1)
            ->call('addConsumable')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('users', [
            'clinic_id' => $this->clinic->id,
            'name' => 'dr. Citra Lestari, Sp.KK',
            'email' => 'citra@onewclinic.com',
            'role' => 'doctor',
        ]);

        $this->assertDatabaseHas('consumables', [
            'clinic_id' => $this->clinic->id,
            'name' => 'SERUM HYALURONIC ACID',
            'category' => 'Topikal & Perawatan',
            'unit' => 'Vial',
        ]);
    }
}
