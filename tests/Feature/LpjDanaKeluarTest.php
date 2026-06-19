<?php

namespace Tests\Feature;

use App\Models\Lpj;
use App\Models\LpjDanaKeluarDivision;
use App\Models\LpjDanaKeluarCategory;
use App\Models\LpjDanaKeluarSubitem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LpjDanaKeluarTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Lpj $lpj;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->lpj  = Lpj::factory()->create(['user_id' => $this->user->id]);
    }

    public function test_store_creates_division_category_and_subitem_records(): void
    {
        $json = json_encode([
            [
                'nama_divisi' => 'Acara',
                'categories'  => [
                    [
                        'nama_kategori' => 'Hadiah Fun Games (ATK)',
                        'subitems'      => [
                            ['rincian_kebutuhan' => 'Pulpen',      'jumlah' => 5, 'satuan' => 'pcs', 'harga_satuan' => 3500],
                            ['rincian_kebutuhan' => 'Buku Tulis',  'jumlah' => 5, 'satuan' => 'pcs', 'harga_satuan' => 5500],
                        ],
                    ],
                    [
                        'nama_kategori' => 'Amplop',
                        'subitems'      => [
                            ['rincian_kebutuhan' => 'Amplop', 'jumlah' => 1, 'satuan' => 'pcs', 'harga_satuan' => 500],
                        ],
                    ],
                ],
            ],
            [
                'nama_divisi' => 'Logistik',
                'categories'  => [
                    [
                        'nama_kategori' => 'Konsumsi',
                        'subitems'      => [
                            ['rincian_kebutuhan' => 'Konsumsi', 'jumlah' => 80, 'satuan' => 'pax', 'harga_satuan' => 20000],
                        ],
                    ],
                ],
            ],
        ]);

        $this->actingAs($this->user)
            ->post(route('lpj.dana-keluar.save', $this->lpj), ['dana_keluar_json' => $json])
            ->assertRedirect();

        $this->assertDatabaseCount('lpj_dana_keluar_divisions', 2);
        $this->assertDatabaseCount('lpj_dana_keluar_categories', 3);
        $this->assertDatabaseCount('lpj_dana_keluar_subitems', 4);

        $acara = LpjDanaKeluarDivision::where('nama_divisi', 'Acara')->first();
        $this->assertNotNull($acara);
        $this->assertEquals(2, $acara->categories->count());

        $kategori = LpjDanaKeluarCategory::where('nama_kategori', 'Hadiah Fun Games (ATK)')->first();
        $this->assertNotNull($kategori);
        $this->assertEquals(1, $kategori->nomor);
        $this->assertEquals(2, $kategori->subitems->count());
    }

    public function test_saving_replaces_existing_dana_keluar_data(): void
    {
        $json = json_encode([
            [
                'nama_divisi' => 'Acara',
                'categories'  => [
                    [
                        'nama_kategori' => 'Hadiah',
                        'subitems'      => [
                            ['rincian_kebutuhan' => 'Pulpen', 'jumlah' => 5, 'satuan' => 'pcs', 'harga_satuan' => 3500],
                        ],
                    ],
                ],
            ],
        ]);

        // First save
        $this->actingAs($this->user)
            ->post(route('lpj.dana-keluar.save', $this->lpj), ['dana_keluar_json' => $json]);

        $this->assertDatabaseCount('lpj_dana_keluar_divisions', 1);

        // Second save with different data
        $json2 = json_encode([
            [
                'nama_divisi' => 'Logistik',
                'categories'  => [
                    [
                        'nama_kategori' => 'Konsumsi',
                        'subitems'      => [
                            ['rincian_kebutuhan' => 'Nasi Box', 'jumlah' => 50, 'satuan' => 'pax', 'harga_satuan' => 25000],
                        ],
                    ],
                ],
            ],
        ]);

        $this->actingAs($this->user)
            ->post(route('lpj.dana-keluar.save', $this->lpj), ['dana_keluar_json' => $json2]);

        // Old data gone, new data present
        $this->assertDatabaseCount('lpj_dana_keluar_divisions', 1);
        $this->assertDatabaseMissing('lpj_dana_keluar_divisions', ['nama_divisi' => 'Acara']);
        $this->assertDatabaseHas('lpj_dana_keluar_divisions', ['nama_divisi' => 'Logistik']);
    }

    public function test_model_total_attributes_compute_correctly(): void
    {
        $division = LpjDanaKeluarDivision::create([
            'lpj_id'      => $this->lpj->id,
            'nama_divisi' => 'Acara',
            'urutan'      => 0,
        ]);

        $category = LpjDanaKeluarCategory::create([
            'division_id'   => $division->id,
            'nama_kategori' => 'ATK',
            'nomor'         => 1,
            'urutan'        => 0,
        ]);

        LpjDanaKeluarSubitem::create([
            'category_id'       => $category->id,
            'rincian_kebutuhan' => 'Pulpen',
            'jumlah'            => 5,
            'satuan'            => 'pcs',
            'harga_satuan'      => 3500,
            'urutan'            => 0,
        ]);

        LpjDanaKeluarSubitem::create([
            'category_id'       => $category->id,
            'rincian_kebutuhan' => 'Buku',
            'jumlah'            => 5,
            'satuan'            => 'pcs',
            'harga_satuan'      => 5500,
            'urutan'            => 1,
        ]);

        $division->load('categories.subitems');
        $category->load('subitems');

        $this->assertEquals(17500.0 + 27500.0, $category->total);
        $this->assertEquals(45000.0, $division->total);
    }

    public function test_empty_division_name_is_skipped(): void
    {
        $json = json_encode([
            [
                'nama_divisi' => '',
                'categories'  => [
                    ['nama_kategori' => 'ATK', 'subitems' => [
                        ['rincian_kebutuhan' => 'Pulpen', 'jumlah' => 1, 'satuan' => 'pcs', 'harga_satuan' => 3500],
                    ]],
                ],
            ],
            [
                'nama_divisi' => 'Acara',
                'categories'  => [
                    ['nama_kategori' => 'Fun Games', 'subitems' => [
                        ['rincian_kebutuhan' => 'Bola', 'jumlah' => 5, 'satuan' => 'pcs', 'harga_satuan' => 17000],
                    ]],
                ],
            ],
        ]);

        $this->actingAs($this->user)
            ->post(route('lpj.dana-keluar.save', $this->lpj), ['dana_keluar_json' => $json]);

        $this->assertDatabaseCount('lpj_dana_keluar_divisions', 1);
        $this->assertDatabaseHas('lpj_dana_keluar_divisions', ['nama_divisi' => 'Acara']);
    }

    public function test_unauthorized_user_cannot_save(): void
    {
        $otherUser = User::factory()->create();

        $json = json_encode([
            ['nama_divisi' => 'Acara', 'categories' => [
                ['nama_kategori' => 'ATK', 'subitems' => [
                    ['rincian_kebutuhan' => 'Pulpen', 'jumlah' => 1, 'satuan' => 'pcs', 'harga_satuan' => 3500],
                ]],
            ]],
        ]);

        $this->actingAs($otherUser)
            ->post(route('lpj.dana-keluar.save', $this->lpj), ['dana_keluar_json' => $json])
            ->assertForbidden();
    }
}
