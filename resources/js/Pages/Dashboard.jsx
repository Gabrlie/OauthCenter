import React, { useEffect, useState } from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';
import axios from 'axios';

export default function Dashboard({ auth }) {
    const [applications, setApplications] = useState([]);

    useEffect(() => {
        // Fetch the authorized applications
        axios.get('/api/oauth-applications')
            .then(response => setApplications(response.data.tokens))
            .catch(error => console.error('Error fetching applications:', error));
    }, []);

    const revokeAuthorization = (tokenId) => {
        if (!confirm('确定要撤销该应用的授权吗？')) return;

        axios.delete(`/api/oauth-applications/${tokenId}`)
            .then(response => {
                alert(response.data.status);
                setApplications(applications.filter(app => app.id !== tokenId));
            })
            .catch(error => console.error('Error revoking authorization:', error));
    };

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">授权管理</h2>}
        >
            <Head title="授权管理" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6 text-gray-900">
                            <h3 className="text-lg font-medium text-gray-900 mb-4">已授权的第三方应用</h3>
                            <ul>
                                {applications.map((app) => (
                                    <li key={app.id} className="mb-4 flex justify-between items-center">
                                        <div>
                                            <span className="font-semibold">{app.client.name}</span>
                                            <span className="text-sm text-gray-600 ml-2">({app.created_at})</span>
                                        </div>
                                        <button
                                            className="text-red-600 hover:text-red-900"
                                            onClick={() => revokeAuthorization(app.id)}
                                        >
                                            撤销授权
                                        </button>
                                    </li>
                                ))}
                            </ul>
                            {applications.length === 0 && (
                                <p className="text-gray-600">当前没有已授权的应用。</p>
                            )}
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
