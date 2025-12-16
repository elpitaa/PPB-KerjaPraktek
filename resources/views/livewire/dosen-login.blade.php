<div>
    <div>
        <form wire:submit.prevent="login">
            <input type="email" wire:model="email" placeholder="Email" required>
            <input type="password" wire:model="password" placeholder="Password" required>
            <button type="submit">Login</button>
            @if (session()->has('error'))
                <p>{{ session('error') }}</p>
            @endif
        </form>
    </div>
</div>
