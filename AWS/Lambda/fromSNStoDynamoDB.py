
import json
import boto3
import os

dynamodb = boto3.client('dynamodb')
AWS_REGION = os.environ['AWS_REGION']

def lambda_handler(event, context):
    #print("Received event: " + json.dumps(event, indent=2))
    
    #Parsing JSON from SNS event received
    message = json.loads(event['Records'][0]['Sns']['Message'])
    
    #Retrieving relevant information from the parsed message (Date, Bucket name, Object Name, Object Size)
    datetime = message['Records'][0]['eventTime']
    bucket = message['Records'][0]['s3']['bucket']['name']
    key = message['Records'][0]['s3']['object']['key']
    size = message['Records'][0]['s3']['object']['size']
    
    #Building the url to the object from retrived information
    url = 'https://' + bucket + 's3-' + AWS_REGION + ".amazonaws.com/" + key
    
    #Insert item in DynamoDB
    dynamodb.put_item(
        Item={
            'filename': {'S': key },
            'eventTime': {'S': datetime},
            'url': {'S': url },
            'itemType': {'S': 'image'}
        },
        TableName='vnaTestDynamoDB',
    )
