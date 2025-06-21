<?php

namespace Wave;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Form extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'fields',
        'is_active',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'fields' => 'array', // Cast the fields attribute to an array
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get a specific field's configuration.
     */
    public function getFieldConfig(string $fieldKey): ?array
    {
        $fields = $this->fields;

        return $fields[$fieldKey] ?? null;
    }

    /**
     * Get the form entries for the form.
     */
    public function entries(): HasMany
    {
        return $this->hasMany(FormEntry::class);
    }
}
