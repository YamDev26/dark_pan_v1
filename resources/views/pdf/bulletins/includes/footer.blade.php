<div class="footer">
  <span class="footer-text">
  {{ $school->email ?? 'Adresse email' }} • {{ $school->addres ?? 'Adresse postale' }} •
  {{ $school->phon }} • {{ $classe->libelle }} • 
  {{ date('d-m-Y').' ~ N°'.$string.'-'.$school->id }}
  </span>
</div>