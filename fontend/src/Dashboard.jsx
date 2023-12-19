import { useNavigate } from 'react-router-dom';
import './Style.css';

function Dashboard() {
    const navigate = useNavigate();
    function logoutSubmit() {
        navigate("/");
    }
    return (
        <div className="form">
            <h3>Dashboard Page</h3>
            <p onClick={logoutSubmit}>Logout</p>
        </div>
    );
}

export default Dashboard;