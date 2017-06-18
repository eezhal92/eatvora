<script type="text/javascript">
  window.App = {
    csrfToken: '{{ csrf_token() }}',
    stripePublicKey: '{{ config('services.stripe.key') }}',
  }
</script>
