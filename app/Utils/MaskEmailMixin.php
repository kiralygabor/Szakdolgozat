<?php

namespace App\Utils;

use Illuminate\Support\Str;
use Illuminate\Support\Stringable;
use InvalidArgumentException;

class MaskEmailMixin
{
    public function maskEmail(): callable
    {
        return function (string $email): Stringable {
            // Basic email validation
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new InvalidArgumentException("Invalid email address.");
            }

            return Str::of($email)
                ->before('@')
                // Mask the handle
                ->when(
                    fn (Stringable $str): bool => $str->length() >= 5,
                    fn (Stringable $str): Stringable => $str->mask('*', 2, -2),
                    fn (Stringable $str): Stringable => $str->when(
                        fn (Stringable $str): bool => $str->length() >= 2,
                        fn (Stringable $str): Stringable => $str->mask('*', 1),
                        fn (Stringable $str): Stringable => $str->mask('*', 0),
                    ),
                )
                ->append('@')
                // Mask the host name
                ->append(
                    Str::of(Str::after($email, '@'))
                        ->beforeLast('.')
                        ->mask('*', 1)
                )
                ->append('.')
                ->append(Str::afterLast($email, '.'));
        };
    }
}
