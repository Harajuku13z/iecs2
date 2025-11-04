@php
    $primary = \App\Models\Setting::get('color_primary', '#A66060');
    $secondary = \App\Models\Setting::get('color_secondary', '#9E5A59');
    $logo = \App\Models\Setting::get('logo', '');
    $logoUrl = $logo ? asset('storage/' . $logo) : null;
@endphp
<div style="background:#f6f8fb; padding:24px; font-family:Arial, Helvetica, sans-serif; color:#222;">
  <div style="max-width:620px; margin:0 auto; background:#fff; border-radius:10px; overflow:hidden; box-shadow:0 5px 25px rgba(0,0,0,.06);">
    <div style="background: linear-gradient(135deg, {{ $primary }}, {{ $secondary }}); padding:24px; text-align:center; color:#fff;">
      @if($logoUrl)
        <img src="{{ $logoUrl }}" alt="IESCA" style="height:48px; display:block; margin:0 auto 8px;" />
      @endif
      <div style="font-size:18px; font-weight:700; letter-spacing:.5px;">IESCA</div>
    </div>
    <div style="padding:28px;">
      {{ $slot }}
      <div style="margin-top:24px; font-size:12px; color:#666; border-top:1px solid #eee; padding-top:16px;">
        Ceci est un message automatique. Merci de ne pas y répondre directement.
      </div>
    </div>
  </div>
</div>


