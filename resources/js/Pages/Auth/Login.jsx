import Checkbox from '@/Components/Checkbox';
import GuestLayout from '@/Layouts/GuestLayout';
import InputError from '@/Components/InputError';
import InputLabel from '@/Components/InputLabel';
import PrimaryButton from '@/Components/PrimaryButton';
import TextInput from '@/Components/TextInput';
import { Head, Link, useForm } from '@inertiajs/react';
import CaptchaInput from "@/Components/CaptchaInput.jsx";
import {useState, useEffect} from "react";
import { message } from 'antd';

export default function Login({ status, canResetPassword }) {
    const { data, setData, post, processing, errors, reset } = useForm({
        email: '',
        password: '',
        remember: false,
        captcha: '', // 增加 captcha 字段
        token: ''
    });

    const setToken = (newToken) => {
        setData('token', newToken);
    };

    const submit = (e) => {
        e.preventDefault();
        post(route('login'), {
            onFinish: () => reset('password', 'captcha'),
        });
    };

    // 第三方登录
    const handleGithubLogin = () => {
        window.location.href = 'https://api.gabrlie.top/auth/github';
    }

    // 第三方登录回调
    const LoginCallback = async () => {
        const error = new URLSearchParams(location.search).get('error');
        if (error) {
            message.error(error);
        }
        const token = new URLSearchParams(location.search).get('token');
        const id = new URLSearchParams(location.search).get('id');
        if (token && id) {
            console.log('登录成功',new URLSearchParams(location.search));
            cookie.save('token', token, {path: '/'});
            cookie.save('id', id, {path: '/'});
            message.success('登录成功');
            // 如果成功，跳转到首页
            history.push('/');
        }
    }

    useEffect(() => {
        LoginCallback();
    }, [location, history]);

    return (
        <GuestLayout>
            <Head title="登录" />

            {status && <div className="mb-4 font-medium text-sm text-green-600">{status}</div>}

            <form onSubmit={submit}>
                <div>
                    <InputLabel htmlFor="email" value="邮箱" />

                    <TextInput
                        id="email"
                        type="email"
                        name="邮箱"
                        value={data.email}
                        className="mt-1 block w-full"
                        autoComplete="username"
                        isFocused={true}
                        onChange={(e) => setData('email', e.target.value)}
                    />

                    <InputError message={errors.email} className="mt-2" />
                </div>

                <div className="mt-4">
                    <InputLabel htmlFor="password" value="密码" />

                    <TextInput
                        id="password"
                        type="password"
                        name="password"
                        value={data.password}
                        className="mt-1 block w-full"
                        autoComplete="current-password"
                        onChange={(e) => setData('password', e.target.value)}
                    />

                    <InputError message={errors.password} className="mt-2" />
                </div>

                {/* 添加验证码输入框 */}
                <CaptchaInput
                    value={data.captcha}
                    onChange={(e) => setData('captcha', e.target.value)}
                    error={errors.captcha}
                    setToken={setToken}
                />

                <div className="block mt-4">
                    <label className="flex items-center">
                        <Checkbox
                            name="remember"
                            checked={data.remember}
                            onChange={(e) => setData('remember', e.target.checked)}
                        />
                        <span className="ms-2 text-sm text-gray-600">记住我</span>
                    </label>
                </div>

                <div className="flex items-center justify-end mt-4">
                    {canResetPassword && (
                        <Link
                            href={route('register')}
                            className="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        >
                            还没有账号？
                        </Link>
                    )}

                    <PrimaryButton className="ms-4" disabled={processing}>
                        登录
                    </PrimaryButton>
                </div>
            </form>

            {/* 第三方登录按钮 */}
            <div className="flex items-center justify-center mt-6">
                <button
                    onClick={handleGithubLogin}
                    className="w-full inline-flex items-center justify-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:bg-gray-700 focus:ring focus:ring-gray-300 active:bg-gray-900 disabled:opacity-25 transition"
                >
                    使用 GitHub 登录
                </button>
            </div>
        </GuestLayout>
    );
}
