import{j as e,W as x,Y as h,a as g}from"./app-Bj1kdrrQ.js";import{G as f}from"./GuestLayout-BrBo71sm.js";import{I as n,T as i,a as c}from"./TextInput-C_eooWZ6.js";import{P as j}from"./PrimaryButton-CxHCmRMR.js";import{C as b}from"./CaptchaInput-CscK11D7.js";import"./ApplicationLogo-B1uJcVZ9.js";function v({className:a="",...m}){return e.jsx("input",{...m,type:"checkbox",className:"rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 "+a})}function F({status:a,canResetPassword:m}){const{data:t,setData:r,post:l,processing:d,errors:o,reset:u}=x({email:"",password:"",remember:!1,captcha:""}),p=s=>{s.preventDefault(),l(route("login"),{onFinish:()=>u("password","captcha")})};return e.jsxs(f,{children:[e.jsx(h,{title:"登录"}),a&&e.jsx("div",{className:"mb-4 font-medium text-sm text-green-600",children:a}),e.jsxs("form",{onSubmit:p,children:[e.jsxs("div",{children:[e.jsx(n,{htmlFor:"email",value:"邮箱"}),e.jsx(i,{id:"email",type:"email",name:"邮箱",value:t.email,className:"mt-1 block w-full",autoComplete:"username",isFocused:!0,onChange:s=>r("email",s.target.value)}),e.jsx(c,{message:o.email,className:"mt-2"})]}),e.jsxs("div",{className:"mt-4",children:[e.jsx(n,{htmlFor:"password",value:"密码"}),e.jsx(i,{id:"password",type:"password",name:"password",value:t.password,className:"mt-1 block w-full",autoComplete:"current-password",onChange:s=>r("password",s.target.value)}),e.jsx(c,{message:o.password,className:"mt-2"})]}),e.jsx(b,{id:"captcha",name:"captcha",value:t.captcha,onChange:s=>r("captcha",s.target.value),error:o.captcha}),e.jsx("div",{className:"block mt-4",children:e.jsxs("label",{className:"flex items-center",children:[e.jsx(v,{name:"remember",checked:t.remember,onChange:s=>r("remember",s.target.checked)}),e.jsx("span",{className:"ms-2 text-sm text-gray-600",children:"记住我"})]})}),e.jsxs("div",{className:"flex items-center justify-end mt-4",children:[m&&e.jsx(g,{href:route("register"),className:"underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500",children:"还没有账号？"}),e.jsx(j,{className:"ms-4",disabled:d,children:"登录"})]})]})]})}export{F as default};
