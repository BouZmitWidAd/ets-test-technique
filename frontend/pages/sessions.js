import api from "../services/api";
import { useEffect, useState } from "react";

export default function Sessions() {
  const [sessions, setSessions] = useState([]);
  const [reservations, setReservations] = useState([]);

  useEffect(() => {
    loadData();
  }, []);

  const loadData = async () => {
    const sessionsRes = await api.get("/sessions");
    const reservationsRes = await api.get("/reservations/me");

    setSessions(sessionsRes.data);
    setReservations(reservationsRes.data);
  };

  const isReserved = (sessionId) =>
    reservations.some(r => r.sessionId === sessionId);

  const reserve = async (sessionId) => {
    await api.post("/reservations", { sessionId });
    loadData();
  };

  const deleteSession = async (sessionId) => {
    await api.delete(`/sessions/${sessionId}`);
    loadData();
  };

  const updateSession = async (sessionId) => {
    alert("Update session (placeholder TP)");
  };

  return (
    <div>
      <h2>Sessions disponibles</h2>

      <table border="1" cellPadding="8">
        <thead>
          <tr>
            <th>Langue</th>
            <th>Date</th>
            <th>Lieu</th>
            <th>Places</th>
            <th>Actions</th>
          </tr>
        </thead>

        <tbody>
          {sessions.map(s => (
            <tr key={s.id}>
              <td>{s.language}</td>
              <td>{s.date}</td>
              <td>{s.location}</td>
              <td>{s.availablePlaces}</td>

              <td>
                {!isReserved(s.id) ? (
                  <button onClick={() => reserve(s.id)}>➕ Réserver</button>
                ) : (
                  <>
                    <button onClick={() => updateSession(s.id)}>✏️</button>
                    <button onClick={() => deleteSession(s.id)}>🗑️</button>
                  </>
                )}
              </td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  );
}