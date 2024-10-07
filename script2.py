import sys

import requests
import json

from jsonpath_ng import jsonpath, parse

url= "https://adcosoft-test.vosfactures.fr/invoices.json?client_id="
token="qLjq0n0k7Bjo0GX9y1LK"
id=sys.argv[1]



param=url+id+"&"+"api_token="+token

response = requests.get(param)

if response.status_code == 200:
    data=response.json()
    
else:
    
    print(f"Erreur lors de la requête: {response.status_code}")


clients_info = []

for client in data:
    
    number_resultat = parse("$.number").find(client)
    date_resultat = parse("$.sell_date").find(client)
    title_resultat = parse("$.kind_text").find(client)  
    price_gross_resultat = parse("$.price_gross").find(client)  

    number = number_resultat[0].value if number_resultat else 'Numéro inconnu'
    date = date_resultat[0].value if date_resultat else 'Date inconnue'
    title = title_resultat[0].value if title_resultat else 'Intitulé inconnu'
    price_gross = price_gross_resultat[0].value if price_gross_resultat else 'Montant inconnu'

  
    client_info = {
        'numero': number,
        'date': date,
        'intitule': title,
        'montant_ttc': price_gross
    }

    clients_info.append(client_info)


print(json.dumps(clients_info, ensure_ascii=False, indent=4))