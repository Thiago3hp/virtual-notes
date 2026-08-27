<script setup lang="ts">
import { ref } from 'vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import InputError from '@/components/InputError.vue';
import AuthBase from '@/layouts/AuthLayout.vue';
import { logout } from '@/routes';

const props = defineProps<{
    numero: string;
}>();

const form = useForm({
    code: '',
});

function confirmar() {
    form.post('/verify-numero', {
        preserveScroll: true,
    });
}

const resending = ref(false);
function reenviar() {
    resending.value = true;
    router.post('/verify-numero/resend', {}, {
        preserveScroll: true,
        onFinish: () => {
            resending.value = false;
        },
    });
}
</script>

<template>
    <AuthBase
        title="Confirme seu número de técnico"
        description="Enviamos um código por WhatsApp"
    >
        <Head title="Verificar número" />

        <p class="mb-4 text-center text-sm text-muted-foreground">
            Enviamos um código para o WhatsApp <strong>{{ props.numero }}</strong>.
            Ele expira em 15 minutos.
        </p>

        <form @submit.prevent="confirmar" class="flex flex-col gap-6">
            <div class="grid gap-2">
                <Label for="code">Código de verificação</Label>
                <Input
                    id="code"
                    v-model="form.code"
                    type="text"
                    inputmode="numeric"
                    autocomplete="one-time-code"
                    maxlength="6"
                    required
                    autofocus
                    placeholder="000000"
                    class="text-center text-2xl tracking-[0.5em]"
                />
                <InputError :message="form.errors.code" />
            </div>

            <Button type="submit" class="w-full" :disabled="form.processing">
                <Spinner v-if="form.processing" />
                Confirmar
            </Button>
        </form>

        <div class="mt-4 flex flex-col items-center gap-2 text-sm text-muted-foreground">
            <button type="button" class="underline underline-offset-4" :disabled="resending" @click="reenviar">
                {{ resending ? 'Enviando...' : 'Reenviar código' }}
            </button>
            <button type="button" class="underline underline-offset-4" @click="router.post(logout().url)">
                Sair
            </button>
        </div>
    </AuthBase>
</template>
