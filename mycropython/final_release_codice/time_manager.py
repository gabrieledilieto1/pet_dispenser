import ntptime
import time

# --- Funzioni orologio NTP ---
def sync_time(max_attempts=5):
    attempts = 0
    while attempts < max_attempts:
        try:
            print(f"Tentativo {attempts + 1} di sincronizzazione NTP...")
            ntptime.settime()
            print("Orario sincronizzato con successo")
            return True
        except Exception as e:
            print("Errore sincronizzazione NTP:", e)
            time.sleep(5)
            attempts += 1

    print("Errore: raggiunto il numero massimo di tentativi")
    return False
