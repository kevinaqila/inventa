<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\Loan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MultiItemLoanTest extends TestCase
{
    public function test_public_loan_form_loads_successfully(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    public function test_can_borrow_multiple_items_successfully(): void
    {
        $items = Item::where('status', 'available')->take(2)->get();
        if ($items->count() < 2) {
            $this->markTestSkipped('Need at least 2 available items in database');
        }

        $postData = [
            'item_ids' => $items->pluck('id')->toArray(),
            'borrower_id_number' => '2201010041',
            'borrower_name' => 'Budi Santoso',
            'study_program' => 'Teknik Informatika',
            'phone_number' => '081234567890',
            'destination_building' => 'Gedung Kuliah Bersama',
            'destination_room' => 'Ruang 304',
            'purpose' => 'Presentasi Tugas Akhir & Seminar',
            'expected_return_at' => now()->addHours(3)->format('Y-m-d H:i:s'),
        ];

        $response = $this->post(route('loans.store'), $postData);

        $loan = Loan::latest()->first();
        $this->assertNotNull($loan);
        $this->assertEquals('Budi Santoso', $loan->borrower_name);
        $this->assertEquals(2, $loan->items()->count());

        $response->assertRedirect(route('loans.success', ['code' => $loan->loan_code]));

        $successResponse = $this->get(route('loans.success', ['code' => $loan->loan_code]));
        $successResponse->assertStatus(200);
        foreach ($items as $item) {
            $successResponse->assertSee($item->name);
            $successResponse->assertSee($item->code);
        }
    }
}
