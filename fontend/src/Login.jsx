import React, { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import './Style.css';

function Login() {
    const navigate = useNavigate();
    const[user, setUser] = useState("");
    const[pass, setPass] = useState("");
    const[error, setError] = useState("");
    const[msg, setMsg] = useState("");

    useEffect(() => {
        setTimeout(function(){
            setMsg("");
        }, 5000);
    }, [msg]);

    const handleInputChange = (e, type) => {
        switch(type) {
            case "user":
                setError("");
                setUser(e.target.value);
                if(e.target.value === "") {
                    setError("Username has left blank");
                }
                break;
            case "pass":
                setError("");
                setPass(e.target.value);
                if(e.target.value === "") {
                    setError("Password has left blank");
                }
                break;
            default:
                
        }
    }

    function loginSubmit() {
        if(user !== "" && pass != "") {
            var url = "localhost:5173/finance-flow/backend/login.php";
            var headers = {
                "Accept": "application/json",
                "Content-type": "application/json",
            };
            var Data = {
                user: user,
                pass: pass,
            };
            fetch(url, {
                method: "POST",
                headers: headers,
                body: JSON.stringify(Data)
            }).then((response) => response.json())
            .then((response) => {
                if(response [0] .result === "Invalid username!" || response [0] .result === "Invalid password" ){
                    setError(response [0] .result);
                }
                else {
                    setMsg(response [0] .result);
                    setTimeout(function(){
                        navigate("/dashboard");
                    }, 5000);
                }
            }).catch((err) => {
                setError(err);
                console.log(err);
            })
        } 
        else {
            setError("All field are required");
        }
    }

    return (
        <div className="form">
            <p>
                {
                    error !== "" ? 
                    <span className="error">{error}</span> :
                    <span className="success">{msg}</span> 
                }
            </p>
            <label>Username</label>
            <input 
                type="text" 
                value={user}
                onChange={(e) => handleInputChange(e, "user")}
            />
            <label>Password</label>
            <input 
                type="password" 
                value={pass}
                onChange={(e) => handleInputChange(e, "pass")}
            />
            <label></label>
            <input 
                type="submit" 
                defaultValue="Login"
                className='button'
                onClick={loginSubmit}
            />
        </div>
    );
}

export default Login;