<div>
    <form wire:submit.prevent="create">
        {{ $this->form }}

        <button type="submit">
            Create
        </button>
    </form>
</div>
