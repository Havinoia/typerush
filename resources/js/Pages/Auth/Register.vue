<script setup>
import AuthLayout from '@/Layouts/AuthLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <AuthLayout>
        <Head title="Register" />

        <div class="mb-8 text-center">
            <h2 class="text-3xl font-black text-white uppercase italic tracking-tighter">Join TypeRush</h2>
            <p class="text-gray-500 mt-2">Create an account to save your best runs</p>
        </div>

        <form @submit.prevent="submit" class="space-y-5">
            <div>
                <InputLabel for="name" value="Name" class="text-gray-400 font-bold uppercase text-xs tracking-widest pl-1 mb-2" />

                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full bg-white/5 border-white/10 text-white rounded-2xl focus:ring-amber-500/50 focus:border-amber-500 py-4 px-6 transition-all"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                    placeholder="John Doe"
                />

                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div>
                <InputLabel for="email" value="Email" class="text-gray-400 font-bold uppercase text-xs tracking-widest pl-1 mb-2" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full bg-white/5 border-white/10 text-white rounded-2xl focus:ring-amber-500/50 focus:border-amber-500 py-4 px-6 transition-all"
                    v-model="form.email"
                    required
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
                    autocomplete="new-password"
                    placeholder="••••••••"
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div>
                <InputLabel for="password_confirmation" value="Confirm Password" class="text-gray-400 font-bold uppercase text-xs tracking-widest pl-1 mb-2" />

                <TextInput
                    id="password_confirmation"
                    type="password"
                    class="mt-1 block w-full bg-white/5 border-white/10 text-white rounded-2xl focus:ring-amber-500/50 focus:border-amber-500 py-4 px-6 transition-all"
                    v-model="form.password_confirmation"
                    required
                    autocomplete="new-password"
                    placeholder="••••••••"
                />

                <InputError class="mt-2" :message="form.errors.password_confirmation" />
            </div>

            <div class="pt-2">
                <PrimaryButton
                    class="w-full justify-center py-4 bg-gradient-to-tr from-amber-400 to-amber-600 hover:from-amber-500 hover:to-amber-700 text-white font-black uppercase italic tracking-tighter text-lg rounded-2xl shadow-2xl shadow-amber-500/20 active:scale-95 transition-all"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    CREATE ACCOUNT
                </PrimaryButton>
            </div>

            <div class="text-center pt-4 border-t border-white/5">
                <p class="text-gray-500 text-sm">
                    Already have an account? 
                    <Link :href="route('login')" class="text-indigo-400 hover:text-indigo-300 font-bold ml-1">Sign in</Link>
                </p>
            </div>
        </form>
    </AuthLayout>
</template>
