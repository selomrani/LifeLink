<form action="{{ route('donations.checkout') }}" method="POST">
    @csrf
    <button type="submit">Donate $10</button>
</form>
