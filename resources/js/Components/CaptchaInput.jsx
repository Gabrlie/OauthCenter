import { useState } from 'react';

export default function CaptchaInput({ id, name, value, onChange, error }) {
    const [captchaSrc, setCaptchaSrc] = useState(route('captcha') + '?' + Date.now());

    const refreshCaptcha = () => {
        setCaptchaSrc(route('captcha') + '?' + Date.now());
    };

    return (
        <div className="mt-4">
            <label htmlFor={id} className="block font-medium text-sm text-gray-700">
                验证码
            </label>
            <div className="flex items-center mt-1">
                <input
                    id={id}
                    name={name}
                    value={value}
                    onChange={onChange}
                    className="block w-full p-2 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                />
                <img
                    src={captchaSrc}
                    onClick={refreshCaptcha}
                    className="ms-2 cursor-pointer p-1 rounded-md"
                    alt="验证码"
                />
            </div>
            {error && <p className="text-sm text-red-600 mt-2">{error}</p>}
        </div>
    );
}
