import json
import boto3
import os
import botocore

#Create a connection with DynamoDB Service
dynamodb = boto3.client('dynamodb')
#Get region of execution from environment variables available by default
AWS_REGION = os.environ['AWS_REGION']
#Get DynamoDB Table name from custom environment variable
# !! DO NOT HARD CODE THIS VALUE !!
var_tableName = os.environ['DYNAMODB_TABLE']

def lambda_handler(event, context):
    try:    
        #Parsing JSON from SNS event received
        message = json.loads(event['Records'][0]['Sns']['Message'])
    
        #Retrieving relevant information from the parsed message (Date, Bucket name, Object Name, Object Size)
        datetime = message['Records'][0]['eventTime']
        bucket = message['Records'][0]['s3']['bucket']['name']
        key = message['Records'][0]['s3']['object']['key']
        size = message['Records'][0]['s3']['object']['size']
    
        #Building the url to the object from retrived information
        url = 'https://' + bucket + 's3-' + AWS_REGION + ".amazonaws.com/" + key
    
        #Inserting item in DynamoDB
        response = dynamodb.put_item(
            Item={
                'filename': {'S': key },
                'eventTime': {'S': datetime},
                'url': {'S': url },
                'itemType': {'S': 'image'}
            },
            TableName = var_tableName,
        )
        
        if (response):
            print("Success! Below the result of what has been inserted in your DynamoDB table " + var_tableName)
            print(response)
            
    except botocore.exceptions.ClientError as e:
        print (e)
