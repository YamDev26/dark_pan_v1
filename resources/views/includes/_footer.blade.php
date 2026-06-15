<div class="container-fluid pt-4 px-4">
  <div class="bg-secondary rounded-top text-center py-1 px-4 my-0" style="border-bottom: 2px solid white; font-size: 12px">
    &copy; <a href="{{ route('dashboard') }}">{{ auth()->user()->school ? ucwords(auth()->user()->school->name):'Your Site Name' }}</a>, All Right Reserved.
  </div> 
</div>