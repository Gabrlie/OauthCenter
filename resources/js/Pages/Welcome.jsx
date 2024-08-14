import {useEffect} from "react";

export default function Welcome({ auth, laravelVersion, phpVersion }) {
    useEffect(()=>{
        window.location.href = auth.user ? '/dashboard' : '/login'
    })

    return <></>
}
