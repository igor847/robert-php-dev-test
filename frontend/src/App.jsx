import { useState } from 'react';
import reactLogo from './assets/react.svg';
import './App.css';

function App() {
  const [data, setData] = useState(null);
  const [loading, setLoading] = useState(false);

  const fetchData = async () => {
    setLoading(true);
    try {
      const res = await fetch('https://jsonplaceholder.typicode.com/todos/1');
      const json = await res.json();
      setData(json);
    } catch (e) {
      setData({ error: 'Помилка запиту' });
    } finally {
      setLoading(false);
    }
  };

  return (
    <>
      <div>
        <a href="https://react.dev" target="_blank">
          <img src={reactLogo} className="logo react" alt="React logo" />
        </a>
      </div>
      <h1>Vite + React + API</h1>
      <div className="card">
        <button onClick={fetchData}>
          {loading ? 'Завантаження...' : 'Отримати TODO'}
        </button>
        {data && <pre>{JSON.stringify(data, null, 2)}</pre>}
      </div>
    </>
  );
}

export default App;
