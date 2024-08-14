import React, { useEffect, useState } from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, usePage } from '@inertiajs/react';
import axios from 'axios';

export default function Dashboard() {
    const { auth } = usePage().props; // 获取用户信息
    const [tokens, setTokens] = useState([]);
    const [status, setStatus] = useState('');

    useEffect(() => {
        // 获取已授权的应用
        axios.get('/api/tokens').then((response) => {
            setTokens(response.data);
        });
    }, []);

    const revokeToken = (id) => {
        axios.post(`/oauth/tokens/revoke/${id}`, {
            _method: 'POST',
            _token: auth.csrf_token,
        })
            .then(() => {
                setStatus('授权已撤销');
                setTokens(tokens.filter((token) => token.id !== id));
            })
            .catch((error) => {
                console.error('撤销授权时出错:', error);
            });
    };

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="font-semibold text-2xl text-gray-800">授权管理</h2>}
        >
            <Head title="授权管理" />

            <div className="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <div className="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div className="px-6 py-4 bg-gray-100 border-b border-gray-200">
                        <h3 className="text-lg font-medium text-gray-900">已授权的应用</h3>
                    </div>

                    <div className="px-6 py-4">
                        {status && (
                            <div className="mb-4 p-4 bg-green-100 text-green-800 rounded">
                                {status}
                            </div>
                        )}

                        {tokens.length === 0 ? (
                            <p className="text-gray-600">您还没有授权任何应用。</p>
                        ) : (
                            <table className="min-w-full divide-y divide-gray-200">
                                <thead>
                                <tr>
                                    <th className="px-4 py-2 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">应用名称</th>
                                    <th className="px-4 py-2 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">最后使用时间</th>
                                    <th className="px-4 py-2 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">操作</th>
                                </tr>
                                </thead>
                                <tbody className="bg-white divide-y divide-gray-200">
                                {tokens.map((token) => (
                                    <tr key={token.id}>
                                        <td className="px-4 py-2 whitespace-nowrap text-sm text-gray-700">{token.client.name}</td>
                                        <td className="px-4 py-2 whitespace-nowrap text-sm text-gray-700">
                                            {token.last_used_at ? token.last_used_at : '从未使用'}
                                        </td>
                                        <td className="px-4 py-2 whitespace-nowrap">
                                            <button
                                                onClick={() => revokeToken(token.id)}
                                                className="px-3 py-1 bg-red-600 text-white text-xs font-medium rounded hover:bg-red-700"
                                            >
                                                撤销授权
                                            </button>
                                        </td>
                                    </tr>
                                ))}
                                </tbody>
                            </table>
                        )}
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
