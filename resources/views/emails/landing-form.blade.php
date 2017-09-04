<p>Dear Admin</p>

<p><strong>{{ $name }}</strong> dari <strong>{{ $company }}</strong> ingin dihubungi tentang eatvora. Alamat email yang bersangkutan adalah <strong>{{ $email }}</strong></p>

<p>Berikut pesan yang bersangkutan:</p>

<div>
  {!! nl2br($formMessage) !!}
</div>
