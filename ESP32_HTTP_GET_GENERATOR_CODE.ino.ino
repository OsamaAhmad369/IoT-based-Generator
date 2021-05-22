/*Code Flow 
   * First of all get data from mySQL DB
   * Then Get Time from NTP server 
   * TO Start the generator there are two main conditions:
   * 1. Press Manual Button and Press Start
   * 2. If the configuration is set to Auto and Timer ON has reached.
   * To off the generator the possible conditions are:
   * 1. Press Manual Button and Press OFF
   * 2. If the configuration is set to Auto and Timer OFF has reached.
   * 3. If the Generator starts two times and didn't start give warning and it will stop.
   */

#include <WiFi.h>
#include <HTTPClient.h>
#include <Arduino_JSON.h>
#include <NTPClient.h>
#include <WiFiUdp.h>

const char* ssid = "Access"; // Change your Hotspot Name
const char* password = "11335577"; // Change your Password
String Website="https://archaean-chills.000webhostapp.com/";
//Your IP address or domain name with URL path
String serverName = Website+"esp-outputs-action.php?action=outputs_state";//Link to get Data from Database
String batteryupdate = Website+"esp-outputs-action.php?action=output_update_battery&id=1&Volt="; //Link to Update Battery VOltage 
String RuntimeUpdate = Website+"esp-outputs-action.php?action=output_update_Runtime&id=1&Run_Time=";//Link to update RUN time of Generator
String GenstatusUpdate = Website+"esp-outputs-action.php?action=output_update_Genstatus&id=1&GEN_status=";//Link to update Gen Status
String reset_runtime=Website+"esp-outputs-action.php?action=output_update&id=1&Run_Time=0";
String auto_config=Website+"esp-outputs-action.php?action=output_update_c&id=1&config=";
String start_button_HMI=Website+"esp-outputs-action.php?action=output_update_start&id=1&Start_but";
String Timer_button_HMI=Website+"esp-outputs-action.php?action=output_update_timer&id=1&timer_but=";
String motor_starter_HMI=Website+"esp-outputs-action.php?action=output_update_m&id=1&m_start=";
String Timer_ON_HMI=Website+"esp-outputs-action.php?action=output_update_timer_ON&id=1&Timer_ON=";
String Timer_OFF_HMI=Website+"esp-outputs-action.php?action=output_update_timer_OFF&id=1&Timer_OFF=";
/*GEN status
 * 0=STOP
 * 1=STARTING
 * 2=RUNNING
 * 3=Warning
 */
// Define NTP Client to get time
WiFiUDP ntpUDP;
NTPClient timeClient(ntpUDP);
// Update interval time set to 5 seconds
const long interval = 5000;
unsigned long previousMillis = 0;

// Variables to save date and time
String formattedDate;
String timeStamp;

String outputsState;
//Config,Start but,Timer but,start,Timer ON,Timer OFF,Run Time
int Config,Start_but,Timer_but,m_start,RunTime;
String Timer_ON,Timer_OFF;
String me;
int shadowFax, num, endB;
byte inByte;
int on_hour,on_min;
int get_hour,get_min,get_sec;
int off_hour,off_min;
int hmi_data_db=0;
int Gen_status=0;
int a=0;
int b=0;
int t=0;
unsigned long starter_motor=0;
unsigned long runtime_count=0;
#define relay_1 27 // To Control the ball valve 
#define relay_2 33 // To activate the starter motor
int i=0;
float batteryLevel;
float generator_voltage;
void setup() {
  Serial.begin(9600);
  
  WiFi.begin(ssid, password);
  Serial.println("Connecting");
  while(WiFi.status() != WL_CONNECTED) { 
    delay(500);
    Serial.print(".");
  }
  Serial.println("");
  Serial.print("Connected to WiFi network with IP Address: ");
  Serial.println(WiFi.localIP());
  pinMode(relay_1,OUTPUT);//Declare Relay 1 pin as OUTPUT
  pinMode(relay_2,OUTPUT);//Declare Relay 2 pin as OUTPUT
  digitalWrite(relay_1,LOW);//Initially set the relay as LOW (if the relay is active HIGH)
  digitalWrite(relay_2,LOW);//Initially set the relay as LOW (if the relay is active HIGH)
  // Initialize a NTPClient to get time
  timeClient.begin();
  // Set offset time in seconds to adjust for your timezone, for example:
  // GMT +1 = 3600
  // GMT +8 = 28800
  // GMT -1 = -3600
  // GMT 0 = 0
  timeClient.setTimeOffset(18000);
}

void loop() {
  if (Serial.available()){ 
    inByte = Serial.read();
    
    if (inByte>47 && inByte<58){ 
      me.concat(char(inByte)); 
    }
   else if (inByte==255){ 
     endB= endB + 1; 
   } 
   
   if (inByte==255 && endB==3){
     shadowFax = me.toInt();
     me=""; 
     endB=0; 
     num = num+ 1;  
   }   
   if (num==1) { 
    
    if(shadowFax==100) //RESET button HMI
    {
     httpGETRequest(reset_runtime);
      }
      else if(shadowFax==101) //Manual button HMI
     {
       httpGETRequest(auto_config+String(0));
     }
      else if(shadowFax==102) //Auto button HMI
     {
       httpGETRequest(auto_config+String(1));
     }
      else if(shadowFax==103) //Start button HMI
     {
       httpGETRequest(start_button_HMI+String(1));
     }
      else if(shadowFax==104) //OFF button HMI
     {
       httpGETRequest(start_button_HMI+String(0));
     }
     else if(shadowFax==105) //Ext. Switch Button HMI
     {
       httpGETRequest(Timer_button_HMI+String(0));
     }
      else if(shadowFax==106) //Timer Button HMI
     {
       httpGETRequest(Timer_button_HMI+String(1));
     }
      else if(shadowFax==107) //Stater Motor Time HMI
     {
       hmi_data_db=1;
       shadowFax=0;
     }
     else if(shadowFax==108) //ON Time HMI
     {
       hmi_data_db=2;
       shadowFax=0;
     }
      else if(shadowFax==109) //OFF Time HMI
     {
       hmi_data_db=3;
       shadowFax=0;
     }
     if(hmi_data_db==1 &&shadowFax>0)
     {
       httpGETRequest(motor_starter_HMI+String(shadowFax));
       hmi_data_db=0;
      }
      else if(hmi_data_db==2 &&shadowFax>0)
      {
         httpGETRequest(Timer_ON_HMI+String(shadowFax));
         hmi_data_db=0;
        }
      else if(hmi_data_db==3 &&shadowFax>0)
       {
         httpGETRequest(Timer_OFF_HMI+String(shadowFax));
         hmi_data_db=0;
        }
     num= 0;
  }
  } 
  else{
  unsigned long currentMillis = millis();
  batteryLevel = map(analogRead(33), 0.0f, 4095.0f, 0, 12.8);//GET analog input of generator Voltage
  generator_voltage = map(analogRead(34), 0.0f, 4095.0f, 0, 12); //GET analog input of generator Voltage
  if(currentMillis - previousMillis >= interval) {
     //Will Check and Update Parameters after 5sec
     // Check WiFi connection status
    if(WiFi.status()== WL_CONNECTED ){ 
      
      outputsState = httpGETRequest(serverName); //GET DB Variables
      Serial.println(outputsState);
      JSONVar myArray = JSON.parse(outputsState);
     
      // JSON.typeof(jsonVar) can be used to get the type of the var
      if (JSON.typeof(myArray) == "undefined") {
        Serial.println("Parsing input failed!");
        return;
      }
      //Typecaste the DB variable 
       Config=atoi(myArray[0]);
       Start_but=atoi(myArray[1]);
       Timer_but=atoi(myArray[2]);
       m_start=atoi(myArray[3]);
       Timer_ON=JSON.stringify(myArray[4]);
       Timer_OFF=JSON.stringify(myArray[5]);
       RunTime=atoi(myArray[6]);
      //Print DB variables
       Serial.print("n11.val=");
       Serial.print(RunTime);
       endNextionCommand();
       String command = "t8.txt=\""+String(batteryLevel,1)+"\"";
       Serial.print(command);
       endNextionCommand();
       Serial.print("n10.val=");
       Serial.print(m_start);
       endNextionCommand();
      
       
       Serial.print(Config);
       Serial.print(",");
       Serial.print(Start_but);
       Serial.print(",");
       Serial.print(Timer_but);
       Serial.print(",");
       Serial.print(m_start);
       Serial.print(",");
       Serial.print(Timer_ON);
       Serial.print(",");
       Serial.print(Timer_OFF);
       Serial.print(",");
       Serial.println(RunTime);
   
      httpGETRequest(batteryupdate+(String) batteryLevel);//Send current battery level to database
      httpGETRequest(GenstatusUpdate+(String) Gen_status);//Send current Gen_status to database
      if(Gen_status==2 && (millis()-runtime_count)>=900000)
      {
        RunTime=RunTime+0.25;// if the motor is in Running Mode, Update Time after 15 mins.
        httpGETRequest(RuntimeUpdate+(String) RunTime);
        runtime_count=millis();
        }
      // save the last HTTP GET Request
      previousMillis = currentMillis;
    }
    else {
      Serial.println("WiFi Disconnected");
    }
  }
  while(!timeClient.update()) {
  timeClient.forceUpdate();
}
  formattedDate = timeClient.getFormattedDate();
  // Extract date
  int splitT = formattedDate.indexOf("T");
  // Extract time
  timeStamp = formattedDate.substring(splitT+1, formattedDate.length()-1);
  get_hour=timeStamp.substring(0,2).toInt();
  get_min=timeStamp.substring(3,5).toInt();
  get_sec=timeStamp.substring(6,8).toInt();
  on_hour=(Timer_ON.substring(1,3)).toInt();
  on_min=(Timer_ON.substring(4,6)).toInt();
  off_hour=(Timer_OFF.substring(1,3)).toInt();
  off_min=(Timer_OFF.substring(4,6)).toInt();  
       // Current TIME
       Serial.print("n0.val=");
       Serial.print(get_hour);
       endNextionCommand();
       Serial.print("n1.val=");
       Serial.print(get_min);
       endNextionCommand();
       Serial.print("n2.val=");
       Serial.print(get_sec);
       endNextionCommand();
      //ON TIME
       Serial.print("n3.val=");
       Serial.print(on_hour);
       endNextionCommand();
       Serial.print("n4.val=");
       Serial.print(on_min);
       endNextionCommand();
       Serial.print("n7.val=");
       Serial.print(00);
       endNextionCommand();
        //Off TIME
       Serial.print("n5.val=");
       Serial.print(off_hour);
       endNextionCommand();
       Serial.print("n6.val=");
       Serial.print(off_min);
       endNextionCommand();
       Serial.print("n8.val=");
       Serial.print(00);
       endNextionCommand();
  
     if(Start_but==1 && Config==0 &&  a==0 )    //Generator will start if Start Button is pressed and Configuration is set to MANUAL
     {
     a=1;
     Serial.println("Generator ON Due to START Button");
     }
     else if(Start_but==0 && Config==0 && a==1) //Generator will OFF if OFF Button is pressed and Configuration is set to MANUAL
     {
     a=0;
     Serial.println("Generator OFF Due to OFF Button");   
     }
     if(Config==1 && get_hour==on_hour && get_min==on_min && t==0)
     {
       t=1;
        Serial.println("Generator ON Due to Timer");
      }
      else if(Config==1 && get_hour==off_hour && get_min==off_min && t==0)
      {
        t=0;
         Serial.println("Generator Off Due to Timer");
        }
     
     if((a==1&&Config==0)||(t==1&&Config==1) && b==0 && i<2)
     {
     digitalWrite(relay_1,HIGH);//Starting Motor will high
     digitalWrite(relay_2,HIGH); //Turn ON gas Valve
     Gen_status=1;//Generator is starting
     starter_motor=millis();
     b=1;
     }
     else if((a==0&&Config==0)||(t==0&&Config==1) && b==1)
     {
      digitalWrite(relay_1,LOW);//Starting Motor will high
      digitalWrite(relay_2,LOW); //Turn ON gas Valve
      Gen_status=0;// Generator is OFF
      a=0;
      t=0;
      i=0;
      b=0;
      }
     if(b==1 && (millis()-starter_motor)>=(m_start*1000))
     {
      digitalWrite(relay_1,LOW); //Turn Off the starter Motor When time exceeds the starter Time
      b=5;
      }
      if(b==5 && generator_voltage<10)
      {
       digitalWrite(relay_2,LOW);
       digitalWrite(relay_1,LOW);
       delay(1500);
       i++;
       b=0;
       }
       else if(b==5 && generator_voltage>10)
       {
        b=1;
         Gen_status=2;    //Generator is Running 
         runtime_count=millis();
        }
        if(i>=2)
        {
          Gen_status=3;    //Warning
          digitalWrite(relay_2,LOW);
          digitalWrite(relay_1,LOW);
          b=0;
          a=0;
          t=0;
          i=0;
          }
          if(Gen_status==0)
          {
            Serial.print("r0.pco=65535");
            endNextionCommand();
            Serial.print("r1.pco=65535");
            endNextionCommand();
            Serial.print("r2.pco=65535");
            endNextionCommand();
            Serial.print("r3.pco=0");//OFF
            endNextionCommand();
            }
            else if(Gen_status==1)
            {
            Serial.print("r0.pco=65535");
            endNextionCommand();
            Serial.print("r1.pco=65504");//Starting
            endNextionCommand();
            Serial.print("r2.pco=65535");
            endNextionCommand();
            Serial.print("r3.pco=65535");
            endNextionCommand();
              }
              else if(Gen_status==2)
            {
            Serial.print("r0.pco=2016");//Runing
            endNextionCommand();
            Serial.print("r1.pco=65535");
            endNextionCommand();
            Serial.print("r2.pco=65535");
            endNextionCommand();
            Serial.print("r3.pco=65535");
            endNextionCommand();
              }
            else if(Gen_status==3)
            {
            Serial.print("r0.pco=65535");
            endNextionCommand();
            Serial.print("r1.pco=65535");
            endNextionCommand();
            Serial.print("r2.pco=63488");//Warning
            endNextionCommand();
            Serial.print("r3.pco=65535");
            endNextionCommand();
              }
  }
}

String httpGETRequest(String serverName) {
  HTTPClient http;
    
  // Your IP address with path or Domain name with URL path 
  http.begin(serverName);
  
  // Send HTTP POST request
  int httpResponseCode = http.GET();
  
  String payload = "{}"; 
  
  if (httpResponseCode>0) {
    Serial.print("HTTP Response code: ");
    Serial.println(httpResponseCode);
    payload = http.getString();
  }
  else {
    Serial.print("Error code: ");
    Serial.println(httpResponseCode);
  }
  // Free resources
  http.end();

  return payload;
}
void endNextionCommand()
{
  Serial.write(0xff);
Serial.write(0xff);
Serial.write(0xff);
}
