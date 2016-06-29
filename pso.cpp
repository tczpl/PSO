#include <iostream>
#include <time.h>
#include <stdlib.h>
#include <iomanip>
#include <fstream>
using namespace std;

#define C2 2.0
#define C1 2.0
#define W 0.5
#define LIZI 30
#define GENERATION 1000
bool yuejie(int miaomi[])
{
     int a[6];
     for (int i=0;i<6;++i) a[i] = miaomi[i];
     
     bool yuejie = false;
     if (a[0]+a[3] > 20 || a[0]+a[3] < 5) {yuejie = true;}
	 if (a[1]+a[4] > 20 || a[1]+a[4] < 4) {yuejie = true;}
	 if (a[2]+a[5] > 20 || a[2]+a[5] < 3) {yuejie = true;}
     
     int k;
     double ba[3]={10.0,10.0,10.0};
     k = 0;
     while (a[0]!=0)//a[0]
     {
           --a[0];
           ba[k] -= 3.0*2;
           ++k;
           if (k==3) k = 0;
     }
     while (a[2]!=0)//a[2]
     {
           --a[2];
           ba[k] -= 0.56*2;
           ++k;
           if (k==3) k = 0;
     }
     
     double er[5]={11.0,11.0,11.0,11.0,11.0};
     k = 0;
     while (a[3]!=0)//a[3]
     {
           --a[3];
           er[k] -= 3.1*2;
           ++k;
           if (k==5) k = 0;
     }
     while (a[4]!=0)//a[4]
     {
           --a[4];
           er[k] -= 2.0*2;
           ++k;
           if (k==5) k = 0;
     }
     
     for (int i=0;i<3;++i) if (ba[i]<0) {yuejie = true;}
     for (int i=0;i<5;++i) if (er[i]<0) {yuejie = true;}
     
     //cout << ba[0] <<" "<< ba[1] <<" "<< ba[2] <<" "<< er[0] <<" "<< er[1] <<" " << er[2] <<" "<< er[3] <<" "<< er[4] << endl;  
     return yuejie;
}

double suiji()
{
       return (double)(rand()/(double)RAND_MAX);
}



int main()
{
	int X[LIZI][6];
	double V[LIZI][6];
	int pBest[LIZI][6];
	int gBest[6]={0};
	double bestlirun[LIZI+1]={0};
	srand(time(NULL)); //每次随机数不一样
	for (int i=0;i<LIZI;++i) //初始化粒子 
	{
        do
        {
		     X[i][0] = 0 + rand()%(21);
		     X[i][1] = 0;
		     X[i][2] = 0 + rand()%(21);
		     X[i][3] = 0 + rand()%(21);
		     X[i][4] = 0 + rand()%(21);
		     X[i][5] = 0;
        }
        while(yuejie(X[i]));

		for (int j=0;j<6;++j)
			pBest[i][j] = X[i][j];
	}
	
	int miaomi;
	miaomi = 0;
	while (miaomi!=GENERATION)
	{
        //suiji
		for (int i=0;i<LIZI;++i)
			for (int j=0;j<6;++j)
			{
				V[i][j] = V[i][j]*W + C1*suiji()*(pBest[i][j]-X[i][j]) + C2*suiji()*(gBest[j]-X[i][j]);
				X[i][j] += (int)V[i][j];
                //X[i][j] = 0.4*X[i][j]+0.3*pBest[i][j]+0.3*gBest[j];
				X[i][1] = 0;
			    X[i][5] = 0;
			    for(int j=0;j<6;++j) if(X[i][j] < 0) X[i][j] = 0;
			}
			
		//yueshu
		for (int i=0;i<LIZI;++i)
            if (yuejie(X[i])) for (int j=0;j<6;++j) X[i][j] = pBest[i][j];
			//X[i][0]=3 ;X[i][1]=0;X[i][2]=9;X[i][3]=5;X[i][4]=6;X[i][5]=0;
        
		
		//gBest
		for (int i=0;i<LIZI;++i)
		{
			double chengben;
			chengben = X[i][0]*30000*3.0 + X[i][3]*40000*3.1 + X[i][4]*2.0*40000 + X[i][2]*30000*0.56;
			double k;
			k = (X[i][0]*168+X[i][3]*200)*1500*0.9 + (X[i][4]*200)*1200*0.7 + (X[i][2]*168)*600*0.2 - chengben;
			k *= 2;
			if (k > bestlirun[i])
			{
				bestlirun[i] = k;
				for (int j=0;j<6;++j)
					pBest[i][j] = X[i][j];
			}
			if (k > bestlirun[LIZI])
			{
				bestlirun[LIZI] = k;
				for (int j=0;j<6;++j)
					gBest[j] = X[i][j];
			}
		}
		
		++ miaomi;
	}
	cout  << gBest[0] << " " << gBest[1] << " " << gBest[2] << " " << gBest[3] << " " << gBest[4] << " " << gBest[5] << " " << setprecision(2) << fixed << bestlirun[LIZI] << endl;
	for (int i=0;i<LIZI;++i)
	    cout << pBest[i][0] << " " << pBest[i][1] << " " << pBest[i][2] << " " << pBest[i][3] << " " << pBest[i][4] << " " << pBest[i][5] << endl;
	
	return 0;
}
