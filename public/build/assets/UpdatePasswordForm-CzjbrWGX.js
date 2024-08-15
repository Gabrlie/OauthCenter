import{r as m,W as h,j as s}from"./app-DQ54Mj8f.js";import{I as n,T as c,a as d}from"./TextInput-S0SkyhFb.js";import{P as v}from"./PrimaryButton-DswpLRZt.js";import{X as g}from"./transition-DrqQ7QXk.js";function C({className:u=""}){const p=m.useRef(),l=m.useRef(),{data:e,setData:a,errors:t,put:w,reset:o,processing:x,recentlySuccessful:f}=h({current_password:"",password:"",password_confirmation:""}),j=r=>{r.preventDefault(),w(route("password.update"),{preserveScroll:!0,onSuccess:()=>o(),onError:i=>{i.password&&(o("password","password_confirmation"),p.current.focus()),i.current_password&&(o("current_password"),l.current.focus())}})};return s.jsxs("section",{className:u,children:[s.jsxs("header",{children:[s.jsx("h2",{className:"text-lg font-medium text-gray-900",children:"更新密码"}),s.jsx("p",{className:"mt-1 text-sm text-gray-600",children:"确保您的帐户使用的是长而随机的密码，以保持安全。"})]}),s.jsxs("form",{onSubmit:j,className:"mt-6 space-y-6",children:[s.jsxs("div",{children:[s.jsx(n,{htmlFor:"current_password",value:"当前密码"}),s.jsx(c,{id:"current_password",ref:l,value:e.current_password,onChange:r=>a("current_password",r.target.value),type:"password",className:"mt-1 block w-full",autoComplete:"current-password"}),s.jsx(d,{message:t.current_password,className:"mt-2"})]}),s.jsxs("div",{children:[s.jsx(n,{htmlFor:"password",value:"新密码"}),s.jsx(c,{id:"password",ref:p,value:e.password,onChange:r=>a("password",r.target.value),type:"password",className:"mt-1 block w-full",autoComplete:"new-password"}),s.jsx(d,{message:t.password,className:"mt-2"})]}),s.jsxs("div",{children:[s.jsx(n,{htmlFor:"password_confirmation",value:"确认新密码"}),s.jsx(c,{id:"password_confirmation",value:e.password_confirmation,onChange:r=>a("password_confirmation",r.target.value),type:"password",className:"mt-1 block w-full",autoComplete:"new-password"}),s.jsx(d,{message:t.password_confirmation,className:"mt-2"})]}),s.jsxs("div",{className:"flex items-center gap-4",children:[s.jsx(v,{disabled:x,children:"保存"}),s.jsx(g,{show:f,enter:"transition ease-in-out",enterFrom:"opacity-0",leave:"transition ease-in-out",leaveTo:"opacity-0",children:s.jsx("p",{className:"text-sm text-gray-600",children:"已保存。"})})]})]})]})}export{C as default};
