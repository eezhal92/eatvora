<html>
  <tr>
    <td><b>Invoice</b></td>
  </tr>
  <tr></tr>
  <tr>
    <td>Dear, {{ $vendor->name }}</td>
  </tr>

  <tr>
    <td>
      Order {{ date_format(date_create($range[0]), 'd M Y') }} until {{ date_format(date_create($range[1]), 'd M Y') }}
    </td>
  </tr>
  <tr></tr>

  @foreach ($mealGroups as $date => $meals)
  <tr></tr>
  <tr>
    <td>{{ date_format(date_create($date), 'l, d M Y') }}</td>
  </tr>
  <tr>
    <td>Menu</td>
    <td>Harga</td>
    <td>Jumlah</td>
    <td>Total</td>
  </tr>
    @php
      $subTotalPrice = $meals->map(function ($meal) {
        return $meal->qty * $meal->price;
      })->sum();
      $subTotalQty = $meals->sum('qty');
    @endphp
    @foreach ($meals as $meal)
    <tr>
      <td>{{ $meal->name }}</td>
      <td>{{ $meal->price }}</td>
      <td>{{ $meal->qty }}</td>
      <td>{{ $meal->qty * $meal->price }}</td>
    </tr>
    @endforeach
    <tr>
      <td></td>
      <td></td>
      <td>{{ $subTotalQty }}</td>
      <td>{{ $subTotalPrice }}</td>
    </tr>
  @endforeach
  <tr></tr>
  <tr>
    <td>Total</td>
    <td></td>
    <td></td>
    <td>
      {{ $total }}
    </td>
  </tr>
  <tr></tr>
  <tr></tr>
  <tr></tr>
  <tr>
    <td>Mugiwara Luffy</td>
  </tr>
  <tr>
    <td>Eatvora Chief Finance Office</td>
  </tr>
</html>
