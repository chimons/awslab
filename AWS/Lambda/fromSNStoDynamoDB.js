var aws = require('aws-sdk');
var dynamodb = new aws.DynamoDB({params: {TableName: process.env.DYNAMODB_TABLE}});
var AWS_REGION = process.env.AWS_REGION;
var tableName = process.env.DYNAMODB_TABLE;


exports.handler = function(event, context) { 
    event = JSON.parse(event.Records[0].Sns.Message);
    var record = event.Records[0];
    var datetime = record.eventTime;
    var s3 = record.s3;
    var bucket = s3.bucket.name;
    var key = s3.object.key;
    var size = s3.object.size;
    var url = 'https://'+bucket+'.s3-'+AWS_REGION+'.amazonaws.com/'+key;
    
    dynamodb.putItem({
        TableName: tableName,
        Item : {
            filename: {S: key },
            eventTime: {S: datetime},
            url: {S: url },
            itemType: {S: 'image'}
        }
    }, function(err, data) {
        if (err) {
            console.error('error','putting item into dynamodb failed: '+err);
        }
        else {
            console.log('great success: '+JSON.stringify(data, null, '  '));
        }
    });

};



 


