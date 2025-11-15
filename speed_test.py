import speedtest
import csv
from datetime import datetime

# Measure internet speed
st = speedtest.Speedtest()
download = st.download() / 1_000_000  # Mbps
upload = st.upload() / 1_000_000
ping = st.results.ping

# Save to CSV (create file if it doesn't exist)
with open('internet_speed.csv', 'a', newline='') as file:
    writer = csv.writer(file)
    writer.writerow([datetime.now(), round(download,2), round(upload,2), ping])

print(f"Recorded at {datetime.now()}: Download={round(download,2)} Mbps, Upload={round(upload,2)} Mbps, Ping={ping}ms")
