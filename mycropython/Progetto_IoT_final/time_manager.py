import ntptime

# --- Funzioni orologio NTP ---
def sync_time():
    try:
        print("Sincronizzo orario con NTP...")
        ntptime.settime()
        print("Orario sincronizzato")
    except Exception as e:
        print("Errore sincronizzazione NTP:", e)
