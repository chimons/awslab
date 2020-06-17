mkdir -p ~/.aws
touch ~/.aws/config
echo '[default]' >> ~/.aws/config
echo "region = $REGION" >> ~/.aws/config


sudo yum update –y
sudo yum install -y httpd24 php56
maxsize=25M
sudo sed -i 's/upload_max_filesize = .*/upload_max_filesize = '${maxsize}'/' /etc/php.ini
sudo service httpd start
sudo chkconfig httpd on
sudo groupadd www
sudo usermod -a -G www ec2-user
sudo su ec2-user
sudo chown -R root:www /var/www
sudo chmod 2775 /var/www
find /var/www -type d -exec sudo chmod 2775 {} +
find /var/www -type f -exec sudo chmod 0664 {} +
cd /var/www/html
wget https://s3.amazonaws.com/awstrainingwavestone/awslab-master.zip
unzip -q awslab-master.zip
mv awslab-master/* .
rm awslab-master.zip
rm -R awslab-master/ install/

echo "S3 name: $S3_NAME"
echo "API gateway URL: $API_GATEWAY_URL"

sed -i "s/'BUCKET_NAME'/\"$BUCKET_NAME\"/g" /var/www/html/index.php
sed -i "s/'S3_REGION'/\"$REGION\"/g" index.php
sed -i "s,http://amazon-api-gateway-url.com/update-me\!,$API_GATEWAY_URL,g" apigatewayclient.js

printf '\033[0;32m  Installation of the AWS LAB environment completed\e[m\n';
printf '\033[0;32m You should be able to access your application thru your browser at : \033[1;32m';
curl -s http://169.254.169.254/latest/meta-data/public-hostname
printf '\e[m\n\n'
