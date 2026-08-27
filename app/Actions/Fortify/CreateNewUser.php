<?php

namespace App\Actions\Fortify;

use App\Concerns\PasswordValidationRules;
use App\Concerns\ProfileValidationRules;
use App\Models\User;
use App\Support\EmailVerificationCode;
use App\Support\TecnicoNumeroValidator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules, ProfileValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            ...$this->profileRules(),
            'password' => $this->passwordRules(),
            'numero_tecnico' => ['required', 'string'],
        ], [
            'numero_tecnico.required' => 'Informe o número de técnico.',
        ])->validate();

        $numero = $input['numero_tecnico'] ?? null;

        // Segundo fator também no cadastro -- sem isso, qualquer pessoa
        // anônima com acesso à tela de registro conseguia criar uma conta.
        if (! TecnicoNumeroValidator::autorizado($numero)) {
            throw ValidationException::withMessages([
                'numero_tecnico' => 'Número de técnico não autorizado.',
            ]);
        }

        // O número só fica "reservado de vez" quando o e-mail é
        // verificado pela primeira vez (ver EmailVerificationCode e
        // VerificationCodeController). Até lá, uma conta cadastrada mas
        // nunca verificada não deve travar esse número pra sempre --
        // por isso a checagem de conflito olha só contas JÁ verificadas.
        $normalizado = TecnicoNumeroValidator::normalizar($numero);
        $jaVinculado = User::whereNotNull('email_verified_at')
            ->get()
            ->contains(fn (User $u) => TecnicoNumeroValidator::normalizar($u->numero_tecnico) === $normalizado);

        if ($jaVinculado) {
            throw ValidationException::withMessages([
                'numero_tecnico' => 'Esse número já está vinculado a outra conta.',
            ]);
        }

        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => $input['password'],
        ]);

        // Guardado desde já (pendente de confirmação) -- vira permanente
        // só quando o código de e-mail for confirmado com sucesso.
        $user->forceFill(['numero_tecnico' => $numero])->save();

        EmailVerificationCode::gerarEEnviar($user);

        return $user;
    }
}
