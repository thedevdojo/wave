<?php

namespace Wave;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'fields' => 'array', // Cast the fields attribute to an array
        'is_active' => 'boolean',
    ];

    /**
     * Get a specific field's configuration.
     *
     * @param string $fieldKey
     * @return array|null
     */
    public function getFieldConfig($fieldKey)
    {
        $fields = $this->fields;

        return $fields[$fieldKey] ?? null;
    }

    /**
     * Get the form entries for the form.
     */
    public function entries()
    {
        return $this->hasMany(FormEntry::class);
    }
}