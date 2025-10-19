@extends('layouts.app')
@section('content')
<h2 class="mb-3">Dashboard Admin</h2>

<div class="row g-3 mb-4">
  <div class="col-md-2"><div class="card"><div class="card-body"><div class="small text-muted">Total Laporan</div><div class="fs-4">{{ $counts['total_reports'] }}</div></div></div></div>
  <div class="col-md-2"><div class="card"><div class="card-body"><div class="small text-muted">Baru</div><div class="fs-4">{{ $counts['baru'] }}</div></div></div></div>
  <div class="col-md-2"><div class="card"><div class="card-body"><div class="small text-muted">Diproses</div><div class="fs-4">{{ $counts['diproses'] }}</div></div></div></div>
  <div class="col-md-2"><div class="card"><div class="card-body"><div class="small text-muted">Selesai</div><div class="fs-4">{{ $counts['selesai'] }}</div></div></div></div>
  <div class="col-md-3"><div class="card"><div class="card-body"><div class="small text-muted">Diskusi</div><div class="fs-4">{{ $counts['discussions'] }}</div></div></div></div>
  <div class="col-md-3"><div class="card"><div class="card-body"><div class="small text-muted">Balasan</div><div class="fs-4">{{ $counts['replies'] }}</div></div></div></div>
</div>

<div class="card">
  <div class="card-body">
    <h5 class="card-title">Grafik Laporan (Harian)</h5>
    <div id="chartDaily" style="height: 320px;"></div>
  </div>
</div>

<div class="card mt-3">
  <div class="card-body">
    <h5 class="card-title">Grafik Laporan (Mingguan)</h5>
    <div id="chartWeekly" style="height: 320px;"></div>
  </div>
</div>

<div class="card mt-3">
  <div class="card-body">
    <h5 class="card-title">Grafik Laporan (Bulanan)</h5>
    <div id="chartMonthly" style="height: 320px;"></div>
  </div>
</div>


@push('scripts')
<!-- ApexCharts (SVG, bukan canvas) -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
  const daily = @json($daily);
  const categories = daily.map(i => i.d);
  const seriesData = daily.map(i => i.c);

  const options = {
    chart: { type: 'line', height: 320 },
    series: [{ name: 'Laporan', data: seriesData }],
    xaxis: { categories },
    stroke: { width: 3 },
  };
  new ApexCharts(document.querySelector("#chartDaily"), options).render();
</script>

<script>
  // Data dari controller
  const weekly = @json($weekly);
  const monthly = @json($monthly);

  // Weekly
  const wkCategories = weekly.map(i => i.week_start);
  const wkSeries = weekly.map(i => i.c);
  new ApexCharts(document.querySelector("#chartWeekly"), {
    chart: { type: 'bar', height: 320 },
    series: [{ name:'Laporan/Minggu', data: wkSeries }],
    xaxis: { categories: wkCategories }
  }).render();

  // Monthly
  const moCategories = monthly.map(i => i.ym);
  const moSeries = monthly.map(i => i.c);
  new ApexCharts(document.querySelector("#chartMonthly"), {
    chart: { type: 'area', height: 320 },
    series: [{ name:'Laporan/Bulan', data: moSeries }],
    xaxis: { categories: moCategories },
    stroke: { width: 3 }
  }).render();
</script>

@endpush
@endsection
