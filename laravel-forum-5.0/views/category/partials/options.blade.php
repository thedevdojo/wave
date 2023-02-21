@foreach ($categories as $cat)
    @if (!isset($hide) || (isset($hide) && $cat->id != $hide->id))
        <option value="{{ $cat->id }}" {{ (isset($category) && $cat->id == $category->id) ? 'selected' : '' }}>
            @for ($i = 0; $i < $cat->depth; $i++)- @endfor
            {{ $cat->title }}
        </option>
    @endif

    @if ($cat->children)
        @include ('forum::category.partials.options', ['categories' => $cat->children])
    @endif
@endforeach
