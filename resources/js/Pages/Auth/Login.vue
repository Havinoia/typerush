<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import AuthLayout from '@/Layouts/AuthLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <AuthLayout>
        <Head title="Log in" />

        <div class="mb-8 text-center">
            <h2 class="text-3xl font-black text-white uppercase italic tracking-tighter">Welcome Back</h2>
            <p class="text-gray-500 mt-2">Log in to track your typing progress</p>
        </div>

        <div v-if="status" class="mb-4 text-sm font-medium text-emerald-400">
            {{ status }}
        </div>

        <form @submit.prevent="submit" class="space-y-6">
            <div>
                <InputLabel for="email" value="Email" class="text-gray-400 font-bold uppercase text-xs tracking-widest pl-1 mb-2" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full bg-white/5 border-white/10 text-white rounded-2xl focus:ring-amber-500/50 focus:border-amber-500 py-4 px-6 transition-all"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="you@example.com"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div>
                <InputLabel for="password" value="Password" class="text-gray-400 font-bold uppercase text-xs tracking-widest pl-1 mb-2" />

                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full bg-white/5 border-white/10 text-white rounded-2xl focus:ring-amber-500/50 focus:border-amber-500 py-4 px-6 transition-all"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                    placeholder="••••••••"
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center group cursor-pointer">
                    <Checkbox name="remember" v-model:checked="form.remember" class="bg-white/5 border-white/10 text-amber-500 focus:ring-amber-500/20" />
                    <span class="ms-2 text-sm text-gray-500 group-hover:text-gray-300 transition-colors">Remember me</span>
                </label>

                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    class="text-sm text-gray-500 hover:text-white transition-colors"
                >
                    Forgot password?
                </Link>
            </div>

            <div>
                <PrimaryButton
                    class="w-full justify-center py-4 bg-gradient-to-tr from-amber-400 to-amber-600 hover:from-amber-500 hover:to-amber-700 text-white font-black uppercase italic tracking-tighter text-lg rounded-2xl shadow-2xl shadow-amber-500/20 active:scale-95 transition-all"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    SIGN IN
                </PrimaryButton>
            </div>

            <div class="text-center pt-4 border-t border-white/5">
                <p class="text-gray-500 text-sm">
                    Don't have an account? 
                    <Link :href="route('register')" class="text-indigo-400 hover:text-indigo-300 font-bold ml-1">Create one</Link>
                </p>
            </div>
        </form>
    </AuthLayout>
</template>
