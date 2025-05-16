<div class="header">
  <h5 class="mb-0">Dashboard Admin</h5>
  <form method="POST" action="{{ route('logout') }}">
    @csrf
    <button class="btn btn-sm btn-light">
      <i class="bi bi-box-arrow-right"></i> Logout
    </button>
  </form>
</div>
