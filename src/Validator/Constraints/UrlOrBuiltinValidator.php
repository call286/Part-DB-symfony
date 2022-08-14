<?php
/**
 * This file is part of Part-DB (https://github.com/Part-DB/Part-DB-symfony).
 *
 * Copyright (C) 2019 - 2020 Jan Böhmer (https://github.com/jbtronics)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published
 * by the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

declare(strict_types=1);

/**
 * This file is part of Part-DB (https://github.com/Part-DB/Part-DB-symfony).
 *
 * Copyright (C) 2019 Jan Böhmer (https://github.com/jbtronics)
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA
 */

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\UrlValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

use function in_array;
use function is_object;

/**
 * The validator for UrlOrBuiltin.
 * It checks if the value is either a builtin ressource or a valid url.
 * In both cases it is not checked, if the ressource is really existing.
 */
class UrlOrBuiltinValidator extends UrlValidator
{
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof UrlOrBuiltin) {
            throw new UnexpectedTypeException($constraint, UrlOrBuiltin::class);
        }

        if (null === $value || '' === $value) {
            return;
        }
        if (!is_scalar($value) && !(is_object($value) && method_exists($value, '__toString'))) {
            throw new UnexpectedValueException($value, 'string');
        }
        $value = (string) $value;
        if ('' === $value) {
            return;
        }

        //After the %PLACEHOLDER% comes a slash, so we can check if we have a placholder via explode
        $tmp = explode('/', $value);
        //Builtins must have a %PLACEHOLDER% construction
        if (in_array($tmp[0], $constraint->allowed_placeholders, false)) {
            return;
        }

        parent::validate($value, $constraint); // TODO: Change the autogenerated stub
    }
}
