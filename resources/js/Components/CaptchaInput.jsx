import { useEffect, useState } from 'react';
import TextInput from '@/Components/TextInput';
import InputLabel from '@/Components/InputLabel';
import InputError from '@/Components/InputError';
import axios from 'axios';

export default function CaptchaInput({ value, onChange, error, setToken }) {
    const [captchaImage, setCaptchaImage] = useState('');

    const generateToken = () => {
        return Math.random().toString(36).substr(2);
    };

    const fetchCaptcha = () => {
        const newToken = generateToken();
        setToken(newToken);

        axios.post('/api/captcha', { token: newToken })
            .then(response => {
                setCaptchaImage(response.data.captcha_image);
            })
            .catch(error => {
                console.error('获取验证码失败', error);
            });
    };

    useEffect(() => {
        fetchCaptcha();
    }, []);

    return (
        <div className="mt-4">
            <InputLabel htmlFor="captcha" value="验证码"/>

            <div className="flex items-center">
                <TextInput
                    id="captcha"
                    name="captcha"
                    value={value}
                    className="mt-1 block w-full"
                    autoComplete="off"
                    onChange={onChange}
                />
                <img
                    src={captchaImage}
                    onClick={fetchCaptcha}
                    className="ms-2 cursor-pointer"
                    alt="点击刷新验证码"
                />
            </div>

            <InputError message={error} className="mt-2"/>
        </div>
    );
}
