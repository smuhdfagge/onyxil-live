{{-- Illustrated MyBooks screen. Rows tick in, VAT is worked out, and the total gets its double underline. --}}
@php
    $rows = [
        ['INV-0142', 'Kabo Foods Ltd', 1250000],
        ['INV-0143', 'Arewa Logistics', 860000],
        ['INV-0144', 'Dala Pharmacy', 415000],
        ['INV-0145', 'Sabon Gari Traders', 732500],
    ];
    $sub = array_sum(array_column($rows, 2));
    $vat = round($sub * 0.075);
    $naira = fn ($n) => '₦'.number_format($n);
@endphp
<div class="ledger" data-ledger aria-label="Illustration of the {{ $product->title }} invoices screen" role="img">
    <div class="ledger-bar">
        <span class="ledger-dots" aria-hidden="true"><i></i><i></i><i></i></span>
        <span class="ledger-url">{{ $product->url ? parse_url($product->url, PHP_URL_HOST) : 'mybooks.cloud' }}</span>
    </div>
    <div class="ledger-body">
        <div class="ledger-head">
            <div>
                <span class="ledger-label">Invoices this month</span>
                <strong class="ledger-big" data-ledger-count="{{ $sub + $vat }}">{{ $naira($sub + $vat) }}</strong>
            </div>
            <span class="ledger-pill">4 paid</span>
        </div>
        <div class="ledger-chart" aria-hidden="true">
            @foreach ([38, 52, 44, 61, 57, 72, 66, 84, 79, 92] as $h)
                <span style="--h: {{ $h }}%; --d: {{ $loop->index }}"></span>
            @endforeach
        </div>
        <table class="ledger-table">
            <tbody>
                @foreach ($rows as $i => [$no, $who, $amt])
                    <tr style="--d: {{ $i }}">
                        <td class="mono">{{ $no }}</td>
                        <td>{{ $who }}</td>
                        <td class="num">{{ $naira($amt) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="sub"><td></td><td>Subtotal</td><td class="num">{{ $naira($sub) }}</td></tr>
                <tr class="vat"><td></td><td>VAT 7.5%</td><td class="num">{{ $naira($vat) }}</td></tr>
                <tr class="total"><td></td><td>Total</td><td class="num"><span class="dbl">{{ $naira($sub + $vat) }}</span></td></tr>
            </tfoot>
        </table>
    </div>
</div>
