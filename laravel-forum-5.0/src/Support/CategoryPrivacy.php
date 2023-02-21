<?php

namespace TeamTeaTime\Forum\Support;

use Illuminate\Foundation\Auth\User;
use Kalnoy\Nestedset\Collection as NestedCollection;
use TeamTeaTime\Forum\Models\Category;

class CategoryPrivacy
{
    const DEFAULT_SELECT = ['*'];
    const DEFAULT_WITH = ['newestThread', 'latestActiveThread', 'newestThread.lastPost', 'latestActiveThread.lastPost'];

    public static function isAccessibleTo(?User $user, int $categoryId)
    {
        return static::getFilteredAncestorsFor($user, $categoryId, $select = ['id'], $with = [])->keys()->contains($categoryId);
    }

    public static function getFilteredIdsFor(?User $user)
    {
        return static::getFilteredFor($user, $select = ['id'], $with = [])->keys();
    }

    public static function getFilteredTreeFor(?User $user)
    {
        return static::getFilteredFor($user)->toTree();
    }

    public static function getFilteredAncestorsFor(?User $user, int $categoryId, array $select = self::DEFAULT_SELECT, array $with = self::DEFAULT_WITH)
    {
        $categories = static::getQuery($select, $with)
            ->ancestorsAndSelf($categoryId)
            ->keyBy('id');

        return static::filter($categories, $user);
    }

    public static function getFilteredDescendantsFor(?User $user, int $categoryId, array $select = self::DEFAULT_SELECT, array $with = self::DEFAULT_WITH)
    {
        $categories = static::getQuery($select, $with)
            ->descendantsAndSelf($categoryId)
            ->keyBy('id');

        return static::filter($categories, $user);
    }

    public static function getFilteredFor(?User $user, array $select = self::DEFAULT_SELECT, array $with = self::DEFAULT_WITH)
    {
        $categories = static::getQuery($select, $with)
            ->get()
            ->keyBy('id');

        return static::filter($categories, $user);
    }

    private static function getQuery(array $select = self::DEFAULT_SELECT, array $with = self::DEFAULT_WITH)
    {
        // 'is_private' and 'parent_id' fields are required for filtering
        return Category::select(array_merge($select, ['is_private', 'parent_id']))
            ->with($with)
            ->defaultOrder();
    }

    private static function filter(NestedCollection $categories, ?User $user, ?NestedCollection $rejected = null)
    {
        if ($rejected == null) {
            $rejected = $categories->reject(function ($category, $id) use ($user) {
                return ! $category->is_private || (! is_null($user) && $user->can('view', $category));
            });
        }

        $categories = $categories->whereNotIn('id', $rejected->keys());
        $rejected = $categories->whereIn('parent_id', $rejected->keys());

        if ($rejected->count() > 0) {
            $categories = static::filter($categories, $user, $rejected);
        }

        return $categories;
    }
}
