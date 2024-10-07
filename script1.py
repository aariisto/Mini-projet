import requests
import json
from jsonpath_ng import jsonpath, parse


url = "https://adcosoft-test.vosfactures.fr/clients.json?api_token="
token = "qLjq0n0k7Bjo0GX9y1LK"
param = url + token

response = requests.get(param)

if response.status_code == 200:
    data = response.json()
    
    clients_info = []
    for client in data:
        email_resultat = parse("$.email").find(client)
        
        if email_resultat and email_resultat[0].value.endswith('@clt-cabco.com'):
            nom = client.get('name', 'Nom inconnu')
            adresse = client.get('adresse', 'Adresse inconnue')
            email = email_resultat[0].value
            id_client = client.get('id', 'ID inconnu') 
            external_id = client.get('external_id', 'External_id inconnu') if client.get('external_id') is not None else 'External_id inconnu'


            client_info = {
                'nom': nom,
                'adresse': adresse,
                'email': email,
                'id': id_client,
                'external_id':external_id
            }
            clients_info.append(client_info)
    
    
    print(json.dumps(clients_info, indent=4))
else:
    print(f"Erreur lors de la requête: {response.status_code}")