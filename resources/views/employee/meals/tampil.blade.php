<p>{{ $menu->name }}</p>
<p>{{ $menu->vendor->name }}</p>
<p>{{ $menu->formattedFinalPrice() }}</p>
<p>{{ $menu->contents }}</p>
<p>{{ $menu->description }}</p>

@if ($renderAddToCartButton)
  <button>Add To Cart</button>
@endif
