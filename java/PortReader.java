package getPortData;

import jssc.*;
import java.sql.*;
import java.time.LocalDateTime;
import java.time.format.DateTimeFormatter;

public class PortReader implements Runnable, SerialPortEventListener {

	static SerialPort com8;
	static int count = 0;
	static StringBuilder buffer = new StringBuilder("               ");
	static String url = "jdbc:mysql://127.0.0.1:3306/connectedhive";
	static Connection con;

	public static void main(String[] args) {

		com8 = new SerialPort("COM8");
		try {
			com8.openPort();
			com8.setParams(SerialPort.BAUDRATE_9600, SerialPort.DATABITS_8, SerialPort.STOPBITS_1,
					SerialPort.PARITY_NONE);
			com8.setFlowControlMode(SerialPort.FLOWCONTROL_RTSCTS_IN | SerialPort.FLOWCONTROL_RTSCTS_OUT);
			com8.addEventListener(new PortReader(), SerialPort.MASK_RXCHAR);
		} catch (SerialPortException e) {
			e.getStackTrace();
		}

		try {
			Class.forName("com.mysql.jdbc.Driver");
		} catch (ClassNotFoundException e1) {
			e1.printStackTrace();
		}
		try {
			con = DriverManager.getConnection(url, "root", "");
		} catch (SQLException e) {
			e.printStackTrace();
		}
	}

	@Override
	public void serialEvent(SerialPortEvent event) {
		if (event.isRXCHAR() && event.getEventValue() > 0) {
			try {
				String receivedData = com8.readString(event.getEventValue());
				manageReceivedData(receivedData);
			} catch (SerialPortException e) {
				e.printStackTrace();
			}
		}
	}

	@Override
	public void run() {
		try {
			Thread.sleep(1);
		} catch (InterruptedException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
	}

	public void manageReceivedData(String data) {
		for (int i = 0; i < data.length(); i++) {
			if (data.charAt(i) != '\n' && count <= 14) {
				buffer.setCharAt(count, data.charAt(i));
				count++;
			}
		}
		if (count == 15)
			writeFile();
	}

	public void writeFile() {
		count = 0;
		System.out.println(buffer);
		String[] parts = buffer.toString().split(";");
		DateTimeFormatter date = DateTimeFormatter.ofPattern("yyyy-MM-dd");
		DateTimeFormatter time = DateTimeFormatter.ofPattern("HH:mm:ss");
		LocalDateTime now = LocalDateTime.now();
		String query = "INSERT INTO `connectedhive`.`mesures` (`Date`, `Heure`, `Temperature`, `Poids`, `Pression`) VALUES (\""
				+ date.format(now) + "\",\"" + time.format(now) + "\"," + parts[0] + "," + parts[1] + "," + parts[2]
				+ ");";
		try {
			Statement stmt = con.createStatement();
			stmt.executeUpdate(query);
		} catch (SQLException e) {
			e.printStackTrace();
		}
	}

}
