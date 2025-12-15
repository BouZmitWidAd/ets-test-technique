import api from "../services/api";
import { useEffect, useState } from "react";

export default function Account() {
  const [user, setUser] = useState({});

  useEffect(() => {
    api.get("/me").then(res => setUser(res.data));
  }, []);

  return (
    <>
      <h2>Mon compte</h2>
      <p>Email : {user.email}</p>
      <p>Nom : {user.nom}</p>
    </>
  );
}