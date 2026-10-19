
@php
    $competition = $competition ?? null;
    $editing = $competition !== null;
@endphp

<form
    method="POST"
    action="{{ $editing ? route('competitions.update', $competition) : route('competitions.store') }}"
    class="competition-form"
>
    @csrf

    @if($editing)
        @method('PUT')
    @endif

    <div class="form-group">
        <label for="name">Nosaukums</label>
        <input
            id="name"
            name="name"
            type="text"
            value="{{ old('name', $competition?->name) }}"
            required
        >
        @error('name')
            <small class="field-error">{{ $message }}</small>
        @enderror
    </div>

    <div class="form-group">
        <label for="description">Apraksts</label>
        <textarea
            id="description"
            name="description"
        >{{ old('description', $competition?->description) }}</textarea>
        @error('description')
            <small class="field-error">{{ $message }}</small>
        @enderror
    </div>

    <div class="form-group">
        <label for="date">Sacensību datums</label>
        <input
            id="date"
            name="date"
            type="date"
            value="{{ old('date', $competition?->date?->format('Y-m-d')) }}"
            required
        >
        @error('date')
            <small class="field-error">{{ $message }}</small>
        @enderror
    </div>

    <div class="form-group">
        <label for="starts_at">Sacensību sākums</label>
        <input
            id="starts_at"
            name="starts_at"
            type="datetime-local"
            value="{{ old('starts_at', $competition?->starts_at?->format('Y-m-d\TH:i')) }}"
            required
        >
        @error('starts_at')
            <small class="field-error">{{ $message }}</small>
        @enderror
    </div>

    <div class="form-group">
        <label for="ends_at">Sacensību beigas</label>
        <input
            id="ends_at"
            name="ends_at"
            type="datetime-local"
            value="{{ old('ends_at', $competition?->ends_at?->format('Y-m-d\TH:i')) }}"
            required
        >
        @error('ends_at')
            <small class="field-error">{{ $message }}</small>
        @enderror
    </div>

    <div class="form-group">
        <label for="course_id">Trase</label>
        <select id="course_id" name="course_id" required>
            <option value="">Izvēlies trasi</option>

            @foreach($courses as $course)
                <option
                    value="{{ $course->id }}"
                    @selected(old('course_id', $competition?->course_id) == $course->id)
                >
                    {{ $course->name }}
                </option>
            @endforeach
        </select>
        @error('course_id')
            <small class="field-error">{{ $message }}</small>
        @enderror
    </div>

    <div class="form-group">
        <label for="max_players">
            Maksimālais dalībnieku skaits
        </label>
        <input
            id="max_players"
            name="max_players"
            type="number"
            min="1"
            value="{{ old('max_players', $competition?->max_players) }}"
        >
        @error('max_players')
            <small class="field-error">{{ $message }}</small>
        @enderror
    </div>

    <div class="form-group">
        <label for="status">Statuss</label>
        <select id="status" name="status" required>
            @foreach([
                'planned' => 'Plānotas',
                'active' => 'Aktīvas',
                'finished' => 'Pabeigtas',
                'cancelled' => 'Atceltas'
            ] as $value => $label)
                <option
                    value="{{ $value }}"
                    @selected(old('status', $competition?->status ?? 'planned') === $value)
                >
                    {{ $label }}
                </option>
            @endforeach
        </select>
        @error('status')
            <small class="field-error">{{ $message }}</small>
        @enderror
    </div>

    <div class="form-group">
        <label for="registration_starts_at">
            Pieteikšanās sākums
        </label>
        <input
            id="registration_starts_at"
            name="registration_starts_at"
            type="datetime-local"
            value="{{ old('registration_starts_at', $competition?->registration_starts_at?->format('Y-m-d\TH:i')) }}"
            required
        >
        @error('registration_starts_at')
            <small class="field-error">{{ $message }}</small>
        @enderror
    </div>

    <div class="form-group">
        <label for="registration_ends_at">
            Pieteikšanās beigas
        </label>
        <input
            id="registration_ends_at"
            name="registration_ends_at"
            type="datetime-local"
            value="{{ old('registration_ends_at', $competition?->registration_ends_at?->format('Y-m-d\TH:i')) }}"
            required
        >
        @error('registration_ends_at')
            <small class="field-error">{{ $message }}</small>
        @enderror
    </div>

    <button type="submit" class="discapp-button">
        {{ $editing ? 'Saglabāt izmaiņas' : 'Izveidot sacensības' }}
    </button>
</form>
