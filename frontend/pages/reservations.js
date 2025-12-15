import api from "../services/api";
import { useEffect, useState } from "react";

export default function Reservations() {
  const [reservations, setReservations] = useState([]);

  useEffect(() => {
    loadReservations();
  }, []);

  const loadReservations = async () => {
    const res = await api.get("/reservations/me");
    setReservations(res.data);
  };

  const cancel = async (id) => {
    await api.delete(`/reservations/${id}`);
    loadReservations();
  };

  return (
    <div>
      <h2>Mes réservations</h2>

      {reservations.length === 0 ? (
        <p>Aucune réservation</p>
      ) : (
        <table border="1" cellPadding="8">
          <thead>
            <tr>
              <th>Langue</th>
              <th>Date</th>
              <th>Lieu</th>
              <th>Action</th>
            </tr>
          </thead>

          <tbody>
            {reservations.map(r => (
              <tr key={r.id}>
                <td>{r.language}</td>
                <td>{r.date}</td>
                <td>{r.location}</td>
                <td>
                  <button onClick={() => cancel(r.id)}>🗑️ Annuler</button>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      )}
    </div>
  );
}