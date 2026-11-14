# Generated username lengths

Wave bounds automatically generated usernames to the application's 191-character column length after slugging the display name. When a candidate is already used, the base is shortened to reserve room for the numeric collision suffix, including multi-digit suffixes. Soft-deleted users continue to reserve their usernames.

Names that produce an empty slug use `user` as a base and follow the same collision handling. Explicit usernames are preserved and existing accounts are not renamed. This applies to user-model creation from password and social registration as well as other creation paths.

Registration form name/email validation is owned by the shared authentication package and requires its separate upstream fix. This change bounds generated usernames independently, including names that expand during transliteration. It does not change the existing database uniqueness constraint or add concurrency retry handling.
