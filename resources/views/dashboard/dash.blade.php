@extends('layouts.app')

@section('content')
    @if (isset($isMahasiswa) && $isMahasiswa)
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="fw-bold mb-2">Status Validasi Dokumen Akademik</h5>
                        @if ($statusValidasi === 'valid')
                            <div class="alert alert-success mb-2">Dokumen pendukung Anda <b>VALID</b>.</div>
                        @elseif($statusValidasi === 'tidak valid')
                            <div class="alert alert-danger mb-2">Dokumen pendukung <b>TIDAK VALID</b>. @if ($catatanValidasi)
                                    <br><small>Catatan: {{ $catatanValidasi }}</small>
                                @endif
                            </div>
                        @else
                            <div class="alert alert-warning mb-2">Status validasi dokumen belum tersedia.</div>
                        @endif
                    </div>
                </div>
                <div class="card mb-4 text-center">
                    <div class="card-body">
                        <h4 class="fw-bold mb-2">Hasil Prediksi Kelulusan</h4>
                        <div class="text-muted mb-3">
                            Diperbarui:
                            {{ $prediksiTerakhir ? \Carbon\Carbon::parse($prediksiTerakhir->tanggal_prediksi)->format('Y-m-d H:i') : '-' }}
                        </div>
                        <div class="d-flex justify-content-center mb-3">
                            @php
                                $warnaLingkaran = '#e6f0ff';
                                if ($prediksiTerakhir && $prediksiTerakhir->hasil_prediksi == 1) {
                                    $warnaLingkaran = '#fff';
                                }
                            @endphp
                            <div
                                style="background:{{ $warnaLingkaran }};border-radius:50%;width:170px;height:170px;display:flex;align-items:center;justify-content:center;">
                                <span style="font-size:2.8rem;font-weight:700;color:#2563eb;">
                                    {{ $prediksiTerakhir ? round($prediksiTerakhir->confidence_score * 100) : '-' }}%
                                </span>
                            </div>
                        </div>
                        <h5 class="fw-bold mb-2">
                            @if ($prediksiTerakhir)
                                {{ $prediksiTerakhir->hasil_prediksi == 0 ? 'Berpotensi Lulus Tepat Waktu' : 'Berisiko Tidak Tepat Waktu' }}
                            @else
                                Belum Ada Prediksi
                            @endif
                        </h5>
                        <div class="text-muted" style="font-size:1.08rem;">
                            @if ($prediksiTerakhir)
                                Berdasarkan analisis data akademik Anda menggunakan algoritma Random Forest, Anda memiliki
                                peluang {{ round($prediksiTerakhir->confidence_score * 100) }}% untuk
                                {{ $prediksiTerakhir->hasil_prediksi == 0 ? 'Berpotensi Lulus Tepat Waktu' : 'Berisiko Tidak Tepat Waktu' }}.
                            @else
                                Belum ada hasil prediksi kelulusan.
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card mb-4">
                    <div class="card-body px-3 py-4">
                        <div class="mb-2">
                            <div style="display:flex;align-items:center;gap:8px;">
                                <i class="bi bi-lightbulb" style="font-size:1.15rem;color:#f7b500;"></i>
                                <span style="font-size:1.18rem;font-weight:700;">Saran untuk Meningkatkan Peluang
                                    Kelulusan</span>
                            </div>
                            <div class="text-muted" style="font-size:0.97rem;margin-top:2px;">Beberapa langkah yang dapat
                                membantu meningkatkan peluang kelulusan</div>
                        </div>
                        <div class="saran-list mt-3 mb-3">
                            @foreach ($saranUtama ?? [] as $i => $saran)
                                <div class="saran-item mb-2 d-flex align-items-center"
                                    style="background:#f1f6fe;border-radius:10px;padding:0.65rem 1rem;">
                                    <span class="saran-num d-inline-flex align-items-center justify-content-center me-3"
                                        style="width:28px;height:28px;background:#2563eb;color:#fff;font-weight:600;border-radius:50%;font-size:1rem;">{{ $i + 1 }}</span>
                                    <span style="font-size:0.98rem;color:#18407a;">{{ $saran }}</span>
                                </div>
                            @endforeach
                        </div>
                        <div class="saran-tips mt-3 p-3"
                            style="background:#fffbe6;border:1px solid #ffe58f;border-radius:8px;">
                            <div style="display:flex;align-items:center;gap:6px;margin-bottom:2px;">
                                <i class="bi bi-lightbulb" style="color:#f7b500;font-size:1.1rem;"></i>
                                <span style="font-weight:600;color:#b08a00;font-size:0.99rem;">Tips Tambahan:</span>
                            </div>
                            <ul class="mb-0 ps-3" style="font-size:0.95rem;color:#8a6d1d;">
                                @foreach ($tipsTambahan ?? [] as $tip)
                                    <li>{{ $tip }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card mb-4">
                    <div class="card-body px-3 py-4" style="background: #fafbfc; border-radius: 12px;">
                        <div class="mb-2">
                            <div style="display:flex;align-items:center;gap:8px;">
                                <i class="bi bi-question-circle" style="font-size:1.3rem;color:#2563eb;"></i>
                                <span style="font-size:1.35rem;font-weight:700;">Frequently Asked Questions</span>
                            </div>
                            <div class="text-muted" style="font-size:0.98rem;margin-top:2px;">Pertanyaan yang sering
                                diajukan tentang sistem prediksi kelulusan</div>
                        </div>
                        <div class="faq-minimal mt-3">
                            <div class="accordion" id="faqAccordion">
                                @foreach ($faq ?? [] as $i => $item)
                                    <div class="faq-item mb-1" style="border-bottom:1px solid #eee;">
                                        <button
                                            class="faq-question btn btn-link w-100 text-start px-0 py-2 d-flex align-items-center justify-content-between"
                                            type="button" data-bs-toggle="collapse"
                                            data-bs-target="#faqCollapse{{ $i }}" aria-expanded="false"
                                            aria-controls="faqCollapse{{ $i }}"
                                            style="font-size:0.97rem;font-weight:500;color:#222;text-decoration:none;">
                                            <span>{{ $item['q'] }}</span>
                                            <span class="faq-arrow"
                                                style="transition:transform 0.2s;font-size:1rem;color:#222;display:inline-block;">
                                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M4.5 6.5L8 10L11.5 6.5" stroke="black" stroke-width="1.5"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </span>
                                        </button>
                                        <div id="faqCollapse{{ $i }}" class="accordion-collapse collapse"
                                            aria-labelledby="faqHeading{{ $i }}" data-bs-parent="#faqAccordion">
                                            <div class="faq-answer text-muted pb-2" style="font-size:0.93rem;">
                                                {{ $item['a'] }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="faq-contact-box mt-4 p-3"
                            style="background:#f6f7f9;border-radius:8px;font-size:1rem;font-weight:500;color:#222;">
                            <span style="font-weight:500;color:#222;">Masih ada pertanyaan?</span> <span class="text-muted"
                                style="font-weight:300;">Hubungi bagian akademik atau dosen pembimbing akademik Anda untuk
                                mendapatkan bantuan lebih lanjut.</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Dashboard</h4>
                </div>
            </div>
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <div class="dashboard-cards">
                <div class="dashboard-card">
                    <div class="dashboard-card-header">
                        <span class="dashboard-card-title">Total Mahasiswa</span>
                        <span class="icon icon-gray"><i class="bi bi-people"></i></span>
                    </div>
                    <div class="stat">{{ $totalMahasiswa ?? 0 }}</div>
                    <div class="desc">Mahasiswa aktif</div>
                </div>
                <div class="dashboard-card">
                    <div class="dashboard-card-header">
                        <span class="dashboard-card-title">Lulus Tepat Waktu</span>
                        <span class="icon icon-green"><i class="bi bi-person-check"></i></span>
                    </div>
                    <div class="stat text-success">{{ $tepatWaktu ?? 0 }}</div>
                    <div class="desc text-success">{{ $persenTepat ?? 0 }}% dari total</div>
                </div>
                <div class="dashboard-card">
                    <div class="dashboard-card-header">
                        <span class="dashboard-card-title">Mahasiswa Berisiko</span>
                        <span class="icon icon-red"><i class="bi bi-exclamation-triangle"></i></span>
                    </div>
                    <div class="stat text-danger">{{ $berisiko ?? 0 }}</div>
                    <div class="desc text-danger">Perlu perhatian khusus</div>
                </div>
                <div class="dashboard-card">
                    <div class="dashboard-card-header">
                        <span class="dashboard-card-title">Akurasi Model</span>
                        <span class="icon icon-blue"><i class="bi bi-graph-up"></i></span>
                    </div>
                    <div class="stat text-primary">{{ $akurasiModel ?? 0 }}%</div>
                    <div class="desc">Random Forest Algorithm</div>
                </div>
            </div>
            <div class="mb-2">
                <ul class="nav nav-tabs nav-justified bg-white rounded-3 shadow-sm" style="overflow:hidden;">
                    <li class="nav-item">
                        <a class="nav-link active fw-semibold" id="tab-mahasiswa" data-bs-toggle="tab"
                            href="#tab-content-mahasiswa">Data Mahasiswa</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-semibold" id="tab-analytics" data-bs-toggle="tab"
                            href="#tab-content-analytics">Analytics</a>
                    </li>
                </ul>
            </div>
            <div class="tab-content">
                <div class="tab-pane fade show active" id="tab-content-mahasiswa">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3">
                                <h5 class="fw-bold mb-0" style="font-size:1.05rem;">Daftar Mahasiswa dan Prediksi</h5>
                                <div class="text-muted" style="font-size:0.90rem;">Klik detail untuk melihat detail
                                    lengkap
                                    mahasiswa</div>
                            </div>
                            <div class="tab-pane show active" id="multi-item-preview">
                                <table id="datatable-buttons" class="table dt-responsive nowrap w-150">
                                    <thead class="table-light">
                                        <tr>
                                            <th>NIM</th>
                                            <th>Nama</th>
                                            {{-- <th>Angkatan</th> --}}
                                            <th>Prodi</th>
                                            <th>SKS Gagal</th>
                                            <th>Prediksi</th>
                                            <th>Confidence Score</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($datatable ?? [] as $row)
                                            <tr>
                                                <td>{{ $row['nim'] }}</td>
                                                <td>{{ $row['nama'] }}</td>
                                                {{-- <td>{{ $row['angkatan'] }}</td> --}}
                                                <td>{{ $row['prodi'] }}</td>
                                                <td><span class="badge bg-warning text-dark">{{ $row['sks_gagal'] }}
                                                        SKS</span>
                                                </td>
                                                <td>
                                                    @if ($row['prediksi'] === 'Tepat Waktu')
                                                        <span class="badge bg-success">{{ $row['prediksi'] }}</span>
                                                    @elseif($row['prediksi'] === 'Berisiko Tidak Tepat Waktu')
                                                        <span class="badge bg-danger">{{ $row['prediksi'] }}</span>
                                                    @else
                                                        <span class="badge bg-secondary">{{ $row['prediksi'] }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="progress" style="height: 20px;">
                                                        <div class="progress-bar bg-primary" role="progressbar"
                                                            style="width: {{ $row['probabilitas'] }}%">
                                                            {{ $row['probabilitas'] }}%
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-danger" id="btnKirimIntervensi"
                                                        data-nama-mahasiswa="{{ $row['nama'] }}"
                                                        data-nim="{{ $row['nim'] ?? '' }}"
                                                        data-prodi="{{ $row['prodi'] ?? '' }}"
                                                        data-email-ortu="{{ $row['email_ortu'] ?? '' }}"
                                                        data-hp-ortu="{{ $row['no_hp_orang_tua'] ?? '' }}"
                                                        data-id-mahasiswa="{{ $row['id_mahasiswa'] ?? '' }}"
                                                        type="button">
                                                        <i class="bi bi-bell me-1"></i> Notif
                                                    </button>
                                                    <a href="{{ route('prediksi.show', $row['id_mahasiswa']) }}"
                                                        class="btn btn-sm btn-secondary ms-1">
                                                        <i class="bi bi-info-circle me-1"></i> Detail
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="tab-content-analytics">
                    <div class="row g-3 mb-3">
                        <div class="col-md-7">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="fw-bold mb-3">Distribusi Prediksi per Angkatan</h5>
                                    @foreach ($angkatanStats ?? [] as $angkatan)
                                        <div class="mb-2">Angkatan {{ $angkatan['tahun'] }} <span
                                                class="float-end">{{ $angkatan['tepat'] }}/{{ $angkatan['total'] }}
                                                ({{ $angkatan['persen'] }}%)
                                            </span></div>
                                        <div class="progress mb-3" style="height: 12px;">
                                            <div class="progress-bar bg-primary"
                                                style="width: {{ $angkatan['persen'] }}%">
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="fw-bold mb-3">Faktor Risiko Utama</h5>
                                    @foreach ($faktorRisiko ?? [] as $f)
                                        <div class="mb-2 p-2 rounded-3 d-flex justify-content-between align-items-center"
                                            style="background:{{ $f['bg'] }};">
                                            <span>{{ $f['label'] }}</span>
                                            <span class="badge bg-{{ $f['color'] }}">{{ $f['level'] }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <h5 class="fw-bold mb-0">Trend Kelulusan Historis</h5>
                            <div class="text-muted mb-3" style="font-size:0.98rem;">Persentase kelulusan tepat waktu vs
                                tidak
                                tepat waktu per angkatan</div>
                            <div id="chart-trend" style="min-height:320px;"></div>
                        </div>
                    </div>
                    <div class="card mt-4">
                        <div class="card-body">
                            <h5 class="fw-bold mb-0">Komposisi Prediksi Kelulusan</h5>
                            <div class="text-muted mb-3" style="font-size:0.98rem;">Proporsi total mahasiswa dengan
                                prediksi
                                Tepat Waktu vs Berisiko Tidak Tepat Waktu</div>
                            <div id="chart-pie" style="min-height:320px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    {{-- Modal Intervensi --}}
    @include('prediksi.form_intervensi', ['mahasiswa' => null])
@endsection

@push('script')
    @if (!isset($isMahasiswa) || !$isMahasiswa)
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var tepatCount = @json($chartData['tepat_count'] ?? []);
                var tidakCount = @json($chartData['tidak_count'] ?? []);
                var tepatPercent = @json($chartData['tepat_percent'] ?? []);
                var tidakPercent = @json($chartData['tidak_percent'] ?? []);
                var angkatan = @json($chartData['angkatan'] ?? []);
                var options = {
                    chart: {
                        type: 'line',
                        height: 320,
                        toolbar: {
                            show: true,
                            tools: {
                                download: true,
                                selection: false,
                                zoom: false,
                                zoomin: false,
                                zoomout: false,
                                pan: false,
                                reset: false,
                                customIcons: []
                            },
                            export: {
                                csv: {
                                    filename: 'trend-kelulusan',
                                    columnDelimiter: ',',
                                    headerCategory: 'Angkatan',
                                    headerValue: 'Value'
                                },
                                png: {
                                    filename: 'trend-kelulusan'
                                },
                                svg: {
                                    filename: 'trend-kelulusan'
                                }
                            }
                        }
                    },
                    series: [{
                            name: 'Lulus Tepat Waktu',
                            data: tepatCount
                        },
                        {
                            name: 'Tidak Tepat Waktu',
                            data: tidakCount
                        }
                    ],
                    xaxis: {
                        categories: angkatan,
                        title: {
                            text: 'Angkatan'
                        },
                        labels: {
                            rotate: -15,
                            style: {
                                fontSize: '13px'
                            }
                        }
                    },
                    colors: ['#22c55e', '#ef4444'],
                    markers: {
                        size: 5
                    },
                    dataLabels: {
                        enabled: true,
                        offsetY: -8,
                        style: {
                            fontSize: '13px',
                            fontWeight: 'bold'
                        },
                        background: {
                            enabled: false
                        },
                        formatter: function(val, opts) {
                            var seriesIndex = opts.seriesIndex;
                            var dataPointIndex = opts.dataPointIndex;
                            var percent = seriesIndex === 0 ? tepatPercent[dataPointIndex] : tidakPercent[
                                dataPointIndex];
                            return val + ' (' + percent + '%)';
                        }
                    },
                    stroke: {
                        curve: 'smooth',
                        width: 3
                    },
                    legend: {
                        position: 'bottom',
                        horizontalAlign: 'center'
                    },
                    yaxis: {
                        min: 0,
                        forceNiceScale: true,
                        labels: {
                            formatter: function(val) {
                                return Math.round(val);
                            }
                        }
                    },
                    grid: {
                        borderColor: '#eee'
                    },
                    tooltip: {
                        y: {
                            formatter: function(val, opts) {
                                var seriesIndex = opts.seriesIndex;
                                var dataPointIndex = opts.dataPointIndex;
                                var percent = seriesIndex === 0 ? tepatPercent[dataPointIndex] : tidakPercent[
                                    dataPointIndex];
                                return val + ' mahasiswa (' + percent + '%)';
                            }
                        }
                    }
                };
                var chart = new ApexCharts(document.querySelector('#chart-trend'), options);
                chart.render();

                // PIE/DONUT CHART
                var pieOptions = {
                    chart: {
                        type: 'donut',
                        height: 320,
                        toolbar: {
                            show: true,
                            tools: {
                                download: true
                            },
                            export: {
                                csv: {
                                    filename: 'komposisi-prediksi'
                                },
                                png: {
                                    filename: 'komposisi-prediksi'
                                },
                                svg: {
                                    filename: 'komposisi-prediksi'
                                }
                            }
                        }
                    },
                    labels: @json($chartPie['labels'] ?? []),
                    series: @json($chartPie['data'] ?? []),
                    colors: ['#22c55e', '#ef4444'],
                    legend: {
                        position: 'bottom',
                        horizontalAlign: 'center'
                    },
                    dataLabels: {
                        enabled: true,
                        style: {
                            fontSize: '15px',
                            fontWeight: 'bold'
                        }
                    },
                    tooltip: {
                        y: {
                            formatter: function(val, opts) {
                                var total = opts.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                var percent = total > 0 ? Math.round(val / total * 100) : 0;
                                return val + ' mahasiswa (' + percent + '%)';
                            }
                        }
                    },
                    plotOptions: {
                        pie: {
                            donut: {
                                size: '65%'
                            }
                        }
                    }
                };
                var pieChart = new ApexCharts(document.querySelector('#chart-pie'), pieOptions);
                pieChart.render();

                document.body.addEventListener('click', function(e) {
                    if (e.target && e.target.closest('#btnKirimIntervensi')) {
                        const btn = e.target.closest('#btnKirimIntervensi');
                        const namaMahasiswa = btn.getAttribute('data-nama-mahasiswa') || '';
                        const nim = btn.getAttribute('data-nim') || '';
                        const prodi = btn.getAttribute('data-prodi') || '';
                        const emailOrtu = btn.getAttribute('data-email-ortu') || '';
                        const hpOrtu = btn.getAttribute('data-hp-ortu') || '';
                        const idMahasiswa = btn.getAttribute('data-id-mahasiswa') || '';
                        // Prefill form
                        document.getElementById('penerima').value = 'Orang Tua/Wali ' + namaMahasiswa;
                        document.getElementById('nomor_email').value = emailOrtu;
                        document.getElementById('metodeEmail').checked = true;
                        document.querySelector('input[name="id_mahasiswa"]').value = idMahasiswa;
                        // Set window.mahasiswaData agar template pesan otomatis benar
                        window.mahasiswaData = {
                            nama: namaMahasiswa,
                            nim: nim,
                            prodi: prodi
                        };
                        // Toggle input sesuai metode
                        document.getElementById('metodeEmail').addEventListener('change', function() {
                            document.getElementById('nomor_email').value = emailOrtu;
                        });
                        document.getElementById('metodeSms').addEventListener('change', function() {
                            document.getElementById('nomor_email').value = hpOrtu;
                        });
                        // Tampilkan modal
                        var modal = new bootstrap.Modal(document.getElementById('modalIntervensi'));
                        modal.show();
                    }
                });
            });
        </script>
    @endif
@endpush

@push('styles')
    <style>
        .dashboard-cards {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            margin-bottom: 24px;
        }

        .dashboard-card {
            flex: 1 1 200px;
            min-width: 220px;
            max-width: 320px;
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            padding: 18px 20px 14px 20px;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .dashboard-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 8px;
        }

        .dashboard-card-title {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 0;
        }

        .dashboard-card .icon {
            font-size: 1.15rem;
            opacity: 1;
            margin-left: 8px;
        }

        .icon-gray {
            color: #888;
        }

        .icon-green {
            color: #219653;
        }

        .icon-red {
            color: #d32f2f;
        }

        .icon-blue {
            color: #2563eb;
        }

        .dashboard-card .stat {
            font-size: 1.45rem;
            font-weight: 700;
            margin-bottom: 0;
        }

        .dashboard-card .desc {
            font-size: 0.93rem;
            color: #7a7a7a;
        }

        .dashboard-card .stat.text-success {
            color: #219653;
        }

        .dashboard-card .stat.text-danger {
            color: #d32f2f;
        }

        .dashboard-card .stat.text-primary {
            color: #2563eb;
        }

        /* Custom tab nav style */
        .custom-tab-nav {
            display: flex;
            justify-content: flex-start;
            background: transparent;
            border: none;
            margin-bottom: 18px;
            gap: 16px;
        }

        .custom-tab-nav .custom-tab-item {
            flex: 0 1 240px;
            text-align: center;
            background: #fff;
            border-radius: 7px 7px 0 0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            font-weight: 500;
            font-size: 1.08rem;
            color: #757575;
            border: none;
            padding: 10px 0 9px 0;
            transition: all 0.2s;
            cursor: pointer;
        }

        .custom-tab-nav .custom-tab-item.active {
            color: #111;
            font-weight: 600;
            background: #fff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        .nav-tabs .nav-link.active {
            background: #fff !important;
            color: #111 !important;
            font-weight: 700;
            border-bottom: 2.5px solid #2563eb !important;
            /* garis bawah biru */
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            z-index: 2;
        }

        .nav-tabs .nav-link {
            color: #757575;
            font-weight: 500;
            background: #f7f7f7;
            border: none;
        }

        @media (max-width: 900px) {
            .dashboard-cards {
                flex-direction: column;
                gap: 14px;
            }

            .dashboard-card {
                max-width: 100%;
                min-width: 0;
            }

            .custom-tab-nav {
                flex-direction: column;
                gap: 8px;
            }

            .custom-tab-nav .custom-tab-item {
                width: 100%;
            }
        }

        .faq-minimal .faq-item {
            border: none;
            background: none;
        }

        .faq-minimal .faq-question {
            background: none;
            border: none;
            outline: none;
            box-shadow: none;
            font-size: 0.97rem;
            font-weight: 500;
            color: #222;
            padding-left: 0;
            padding-right: 0;
            transition: color 0.2s;
        }

        .faq-minimal .faq-question[aria-expanded="true"] {
            color: #2563eb;
        }

        .faq-minimal .faq-question[aria-expanded="true"] .faq-arrow {
            transform: rotate(180deg);
        }

        .faq-minimal .faq-arrow {
            font-size: 1rem;
            color: #222;
            margin-left: 8px;
        }

        .faq-minimal .faq-answer {
            font-size: 0.93rem;
            color: #444;
            background: none;
            border: none;
            padding-top: 0.2rem;
        }

        .faq-contact-box {
            background: #f6f7f9;
            border-radius: 8px;
            font-size: 0.98rem;
            font-weight: 500;
            color: #222;
        }

        /* New styles for saran and tips */
        .saran-list {
            margin-bottom: 20px;
        }

        .saran-item {
            margin-bottom: 10px;
            padding: 10px 15px;
            border-radius: 8px;
            display: flex;
            align-items: center;
        }

        .saran-num {
            width: 28px;
            height: 28px;
            background-color: #2563eb;
            color: white;
            border-radius: 50%;
            font-size: 1rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
        }

        .saran-item span {
            font-size: 0.98rem;
            color: #18407a;
        }

        .saran-tips {
            padding: 15px;
            border-radius: 8px;
            background-color: #fffbe6;
            border: 1px solid #ffe58f;
        }

        .saran-tips h6 {
            margin-bottom: 10px;
            font-size: 0.99rem;
            color: #b08a00;
            font-weight: 600;
        }

        .saran-tips ul {
            font-size: 0.95rem;
            color: #8a6d1d;
            padding-left: 20px;
        }

        .saran-tips li {
            margin-bottom: 5px;
        }

        .row.justify-content-center>.col-md-8>.card {
            margin-bottom: 16px !important;
        }

        @media (max-width: 900px) {
            .row.justify-content-center>.col-md-8>.card {
                margin-bottom: 12px !important;
            }
        }
    </style>
@endpush
